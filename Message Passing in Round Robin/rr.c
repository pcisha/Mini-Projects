/* CSCI - B673
 * Name: ex1
 * Filename: rr.c
 * Author: Prachi Shah
 * Year: 2014 */
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

#include "mpi.h"

int main(int argc, char* argv[]) {

    //MPI variables
    MPI_Status mpiStatus;
    int myRank = 0, tag = 1, destProc = 0, numProcesses = 0;

    int minSz = 0, szStep = 0, maxSz = 0;               	//Minimum and Maximum size of array, szStep
    int msgSz = 0, size = 0;                      		//Size of Message
    int numOfCycles = 0, cycleNumber = 0;                	//Total number of cycles and Cycle number
    int buff = 0, i = 0, j = 0;
    char *sizesFile = "sizes", *resultFile = "result";   	//To store 'sizes' and 'result' file names
    FILE *fpSizes, *fpResult;     				//File pointers to 'sizes' and 'result'
    double *messg;     	        				//Message buffer
    double startTime, endTime;                  		//Start time counter and End time counter

    //Execution starts
    MPI_Init(&argc, &argv);
    MPI_Comm_rank(MPI_COMM_WORLD, &myRank);
    MPI_Comm_size(MPI_COMM_WORLD, &numProcesses);

    //Read 'sizes' file to know the values of minSz, szStep and maxSz
    if(myRank == 0) {  							//When myrank is 0, the initial process 
	fpSizes  = fopen(sizesFile, "r");				//'sizes' file has inputs
	fpResult = fopen(resultFile, "a+"); 				//'result' file stores output

	if(fpSizes == NULL || fpResult == NULL) {			//Check if files are not empty
		printf("\n Error opening file. \n");
		MPI_Abort(MPI_COMM_WORLD, 0);				//Terminate MPI execution environment
	}

	//Print output results
	fscanf(fpSizes, "%d %d %d", &minSz, &szStep, &maxSz);
	fprintf(fpResult, "\n ===========================================================================");
	fprintf(fpResult, "\n #processes \t msgSize \t cycles \t\t totaltime");
	fprintf(fpResult, "\n ===========================================================================");
    }

    //MPI Broadcast the Message
    MPI_Bcast(&minSz, 1, MPI_INT, 0, MPI_COMM_WORLD);
    MPI_Bcast(&szStep, 1, MPI_INT, 0, MPI_COMM_WORLD);
    MPI_Bcast(&maxSz, 1, MPI_INT, 0, MPI_COMM_WORLD);

    buff = pow(10, maxSz);						//Set the array values; Initialize the array
    messg = (double *)malloc(buff*sizeof(double));			//Memory allocation
    
    for(size = minSz; size < maxSz; size += szStep) {
	startTime = MPI_Wtime();					//Start time
	
	msgSz = pow(10,size);						//Calcuate the size of message
	numOfCycles = ((maxSz - size) > 1) ? (maxSz - size) : 1;	//Calcuate the number of cycles

      	for(cycleNumber = 0; cycleNumber < numOfCycles; cycleNumber++) {
		if((myRank == 0) && (cycleNumber == 0)) {		//Intial process
			memset(messg, 0, msgSz*sizeof(double));
			MPI_Send(messg, msgSz, MPI_DOUBLE, (myRank + 1)%numProcesses, tag, MPI_COMM_WORLD);
		}

		if(((myRank - 1)%numProcesses) < 0)			//If only one process is invloved
			destProc = (numProcesses - 1);
		else
			destProc = (myRank - 1)%numProcesses;

		//Receive message from ((myrank - 1) % numProcesses)
		MPI_Recv(messg, msgSz, MPI_DOUBLE, destProc, tag, MPI_COMM_WORLD, &mpiStatus);

		//Increment by 1, the contents of the message
		for(i = 0; i < msgSz; i++)
		    messg[i]++;

		if((myRank == 0) && (cycleNumber == (numOfCycles - 1))) {
			break;
		}

		//Send message to (myrank + 1)%numProcesses
		MPI_Send(messg, msgSz, MPI_DOUBLE, (myRank + 1)%numProcesses, tag, MPI_COMM_WORLD);
	}

	endTime = MPI_Wtime();					//End time

	//To print time taken
	if(myRank == 0) {
		fprintf(fpResult, "\n%d\t\t%d\t\t%d\t\t\t%0.16e", numProcesses, msgSz, numOfCycles, endTime - startTime);
	}
    }
    free(messg);				//Free memory

    if(myRank == 0) {				//Close file
	fclose(fpSizes);
	fclose(fpResult);
    }

  	MPI_Finalize();
  	return 0;
}
