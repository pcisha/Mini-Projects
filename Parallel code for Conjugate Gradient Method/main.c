/* CSCI - B673
 * Name: ex3
 * Filename: main.c
 * Author: Prachi Shah
 * Year: 2014 */
 
#include<stdio.h>
#include<stdlib.h>
#include<string.h>
#include<math.h>

#include "mpi.h"
#include "CGsetup.h"

//Function declarations
double dotFunc(int n_local, double *x, double *y);
void matMultCsrFunc(int n_local, int n_global,  int local_rank, int nnz_local, double *xLocal, int *rLocal, int *cGlobal, int *firstrow, int *lastrow,int p, double *data, double *r);
void daxpyFunc(double *x, double *y, double *first, double second, int n_local);
int calcConGrad(int n_local, int n_global, int local_rank, int nnz_local,double *bLocal, double *xLocal, int *rLocal, int *cGlobal, int *firstrow, int *lastrow, double *data, int maxIterations, double toleranceResidue, int p, double *hist);

int main(int argc, char **argv)
{
	//Variable declarations
	int p,root = 0, i, value = 0, local_rank, nnz_local, n_local, n_global;
	int *firstrow, *lastrow, job, type = -2, maxIterations; 		//Maximum iterations
	double toleranceResidue; 						//Residue tolerance
	double *data, *xLocal, *bLocal;						//Matrix data values
	FILE *fSizes = NULL, *fResult = NULL, *fResidual = NULL;		//File pointers	[Results will be stored in result and residue file]
	char *sizes = "sizes", *result = "result", *residual = "residual";	//To read data from files
	double *hist = NULL;               					//Residue history iteration
	int *rLocal,  *cGlobal;							//Local values for rows, global values for columns
	double startTime, endTime;                            			//Start and end times

	//MPI initializations
	MPI_Init(&argc, &argv);
	MPI_Comm_rank(MPI_COMM_WORLD, &local_rank);
	MPI_Comm_size(MPI_COMM_WORLD, &p);

	if(local_rank==root) {
	printf("\n Initiating MPI..."); }

	if(local_rank == root) {
	fSizes = fopen(sizes, "r");		//Get data
	fResult = fopen(result, "a+"); 		//Store result
	fResidual = fopen(residual,"a+");	//Store result

	if(fSizes == NULL || fResult == NULL || fResidual == NULL) {	//Error check
	printf("\n File read error!");
	MPI_Abort(MPI_COMM_WORLD, 0); 
	}	

	fscanf(fSizes,"%d %d %le %d", &n_global, &job, &toleranceResidue, &maxIterations);
	fprintf(fResult, "\n actual#iters \t , final sqrt(rho) \t , time in seconds for CG solve (not including setup) \t , n  , p \t \n");                                   
	fprintf(fResult,"\n========================================================================================================\n");
	hist = (double*) malloc(sizeof(double) * maxIterations);
	}

	//Broadcasting values
	MPI_Bcast( &n_global, 1, MPI_INT, 0, MPI_COMM_WORLD);
	MPI_Bcast( &job, 1, MPI_INT, 0, MPI_COMM_WORLD);
	MPI_Bcast( &toleranceResidue, 1, MPI_DOUBLE, 0, MPI_COMM_WORLD);
	MPI_Bcast( &maxIterations, 1, MPI_INT, 0, MPI_COMM_WORLD);

	//CSR data structure values
	lastrow = (int*) malloc (sizeof(int)* p);
	firstrow = (int*) malloc (sizeof(int)* p);

	//Call CGsetup function with the determined set of values
	CGsetup(&type, &n_global, firstrow, lastrow, &p, &nnz_local, NULL, NULL, NULL, &local_rank, NULL);
	n_local = lastrow[local_rank]- firstrow[local_rank] + 1;	//Set n local value

	//Memory allocation
	rLocal = (int*) malloc(sizeof(int)* (n_local + 1));
	cGlobal = (int*) malloc(sizeof(int)* (nnz_local));
	xLocal = (double*) malloc(sizeof(double)* (n_local));
	bLocal = (double*) malloc(sizeof(double)* (n_local));
	data = (double*) malloc(sizeof(double)* (nnz_local));

	memset(xLocal, 0, n_local * sizeof(double));
	
	//CSR structures values call	(Call CGsetup())
	CGsetup(&job, &n_global, firstrow, lastrow, &p, &nnz_local, rLocal, cGlobal, data, &local_rank, bLocal);

	startTime = MPI_Wtime();	//Timer begins [to calculate CG speedup]

	//Calculating conjugate gradient here:
	value = calcConGrad(n_local, n_global, local_rank, nnz_local, bLocal, xLocal, rLocal, cGlobal, firstrow, lastrow, data, maxIterations, toleranceResidue, p,hist);

	endTime = MPI_Wtime();	//End timer

	//Record values in "result" and "residual"" files
	if(local_rank == root) {
		fprintf(fResult, " %d \t \t %1.16e \t  %1.16e \t  %d \t \t %d \t \n", value,(value > 0 ? hist[value] : 0),(value > 0? endTime - startTime :0), n_global, p);
		for(i = 0; i <= value && value > 0; i++) {
			fprintf(fResidual, " %1.16e \n", hist[i]); 
		}
		fprintf(fResidual, "\n #-------------------------------------------------------\n");
		fclose(fSizes);	//Close file pointers
		fclose(fResult);
		fclose(fResidual);
		free(hist);	//Free memory
	}

	//Free memory
	free(firstrow);
	free(lastrow);
	free(rLocal);
	free(cGlobal);
	free(xLocal);
	free(bLocal);
	free(data); 

	MPI_Finalize();
	return 0;
}

//Functions==========================
double dotFunc(int n_local, double *x, double *y) {
	double localSum = 0, finalSum = 0;
	int i;
	for(i = 0;i < n_local;i++) {
		localSum += x[i] * y[i]; }
	MPI_Allreduce(&localSum, &finalSum, 1 , MPI_DOUBLE, MPI_SUM, MPI_COMM_WORLD);	//Final Sum
	return finalSum;
}

void matMultCsrFunc(int n_local, int n_global,  int local_rank, int nnz_local, double *xLocal, int *rLocal, int *cGlobal, int *firstrow, int *lastrow,int p, double *data, double *r) {
	int q, s; 
	double *xGlobal;            //Global vector xX
	xGlobal = (double*) malloc(n_global* sizeof(double));	//Mem. allocation
	memcpy( &xGlobal[firstrow[local_rank]], xLocal, n_local*sizeof(double));
	for(q = 0; q < n_local; q++) {
		r[q] = 0;
		for(s = rLocal[q]; s < rLocal[q + 1]; s++) {
			r[q] = r[q] + data[s] * xGlobal[cGlobal[s]]; 
		}
	}
}

void daxpyFunc(double *x, double *y, double *first, double second, int n_local) {	//Daxpy operation
	int j;
	for(j = 0; j < n_local; j++) {
		first[j] = second * x[j] + y[j]; }
}


int calcConGrad(int n_local, int n_global, int local_rank, int nnz_local,double *bLocal, double *xLocal, int *rLocal, int *cGlobal, int *firstrow, int *lastrow, double *data, int maxIterations, double toleranceResidue, int p, double *hist) {
	int i = 0, root = 0;
	//Residual, Search dimention, Temp vector, residual norml square; step sizes, conjugate scalar balue
	double *resd, *dimen, *tempV, resN2, resN1, stepSize, conScaVal;

	dimen = (double *)malloc (n_local*sizeof(double));
	resd = (double *)malloc (n_local*sizeof(double));
	tempV = (double *)malloc (n_local*sizeof(double));

	if(local_rank == root)
	memset(hist, 0, maxIterations*sizeof(double));	//Initializa

	//resd = b - A*x
	matMultCsrFunc(n_local, n_global, local_rank, nnz_local, xLocal, rLocal, cGlobal, firstrow, lastrow, p, data, tempV);
	daxpyFunc(tempV, bLocal, resd, -1, n_local);

	//dimen=resd
	memcpy(dimen, resd, n_local* sizeof(double));

	//rho = resd'.resd
	resN1 = dotFunc(n_local, resd, resd);

	//Initialize iteration to start from 0.
	//Record resdual iterations history
	i=0;
	if(local_rank == root)
	hist[i] = sqrt(resN1);

	//Begin
	while((i < maxIterations) && (resN1 > toleranceResidue * toleranceResidue)) {
		//stepSize = resd'.resd/ dimen.(A*dimen)
		matMultCsrFunc(n_local, n_global, local_rank, nnz_local, dimen, rLocal, cGlobal, firstrow, lastrow, p, data, tempV);
		stepSize = resN1/dotFunc(n_local, dimen, tempV);

		//x = x+stepSize*dimen 
		daxpyFunc(dimen, xLocal, xLocal, stepSize, n_local);

		//resd = resd - stepSize * tempV
		daxpyFunc(tempV, resd, resd, -stepSize, n_local);

		//conScaVal  = resd' . resd/ resN2' . resN1
		resN2 = dotFunc(n_local, resd, resd);
		conScaVal = resN2/ resN1;

		//resN1 = resN2   [simple allocation]
		resN1 = resN2;
		i++;

		//dimen = resd + conScaVal * dimen;
		daxpyFunc(dimen, resd, dimen, conScaVal, n_local);

		//Residue histry
		if(local_rank ==root) {
			hist[i] = sqrt(resN1);
			printf("Iteration : %d, Residue value : %1.16e\n", i,  hist[i]); }
	}

	free(dimen);
	free(tempV);
	free(resd);
	return i;
}

