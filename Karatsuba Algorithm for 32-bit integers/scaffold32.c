/* CSCI B503
* Final Project
* scaffold32.c
* Prachi Shah(pracshah)
* 12/04/2013	*/
#include <stdio.h>
#include <stdarg.h>
#include <stdlib.h>
#include <string.h>
#include <gmp.h>
#include <time.h>
#include "scaffold32.h"

#define wordLen 4294967296llu;		//2^32 size
//NumA is first number for multiplication; NumB is second number for multiplication; NumC stores the final result of the function's calculations
//NumAlen is first number's wordlength; NumBlen is second number's wordlength; //NumClen is results' length; 

//Use of Algorithm 1.4: Addition	[Reference: The Analysis of Algorithms book by Paul Walton Purdom, Jr; Cynthia A. Brown]
//This function is used in Step 1, 2, 8, 9 of Algorithm 5.2	[Reference: The Analysis of Algorithms book by Paul Walton Purdom, Jr; Cynthia A. Brown]

void addition(unsigned int *NumA, unsigned int *NumB, unsigned int *NumC, unsigned int NumAlen, unsigned int NumBlen, unsigned int *NumClen) {	//NumALen is wordlength of NumA
	unsigned long long val;
	unsigned int toCarry=0, k=0;

	//Perfrom Addition of two numbers as per the Algorithm 1.4
    for(k=0;k<NumAlen;k++) {	//Length of a NumA is bigger than length of NumB since NumA is a bigger length number
        if(k<NumBlen) {			//Until Length of NumA=length of NumB	
            val=(unsigned long long)NumA[k] + NumB[k] + toCarry; }	
         else {					//When the second number is smaller than the first number
            val=(unsigned long long)NumA[k] + toCarry; }
         NumC[k]=val% wordLen;	//Store addition result here
         toCarry=val /wordLen;	//Store carry here
    }
	while(toCarry>0) {			//If more carry is produced in case the two numbers are not of the same length
		val=(unsigned long long)toCarry;
        NumC[k]=val% wordLen;	//Store addition result here
        toCarry=val /wordLen;	//Store carry here
        k++;
    }
     *NumClen=k;
}

//This function is used in Step 6 of Algorithm 5.2	[Reference: The Analysis of Algorithms book by Paul Walton Purdom, Jr; Cynthia A. Brown]
void subtraction(unsigned int *NumA, unsigned int *NumB, unsigned int *NumC, unsigned int NumAlen, unsigned int NumBlen, unsigned int *NumClen) {
    unsigned long long val2=0;
	unsigned int toCarry=0, m=0;
	
	*NumClen=NumAlen;	//Pointer is pointing to the start of array sized: size of (NumAlen + NumBlen)
	//Perfrom Subtraction of two numbers
    for(m=0;m<NumAlen;m++) {
        if(NumA[m]<NumB[m]) {
			val2=NumA[m]- NumB[m]+ wordLen;	//To handle borrow
			NumA[m+1]-- ; }
        else
			val2=NumA[m]- NumB[m];	//No borrow
        NumC[m]=val2;	//Store subtraction result here      
    }
}

//Uses Multiplication Algorithm 1.8 to perform 32-bit multiplication 		[Reference: The Analysis of Algorithms book by Paul Walton Purdom, Jr , Cynthia A. Brown; Project Turn in 2 of Prachi Shah(pracshah)]
void multiplyFunc(unsigned int *NumA, unsigned int *NumB, unsigned int *NumC, unsigned int NumAlen, unsigned int NumBlen, unsigned int *NumClen) {
		int y,z=0;
		//Cast NumA, NumB to short integers of 32 bits 
		unsigned int *MultOne=(unsigned int *) NumA, *MultTwo=(unsigned int *) NumB, *MultResult=(unsigned int *) NumC;
		unsigned long long answer=0, carry=0;
		
		*NumClen=NumAlen + NumBlen;	//Pointer is pointing to the start of array sized: size of (NumAlen + NumBlen)
		for (y=0;y<*NumClen;y++) {
			MultResult[y]=0; }		//Set MultResult to 0

		for(y=0;y<NumAlen;y++) {
			carry=0;
			for(z=0;z<NumBlen;z++) {
				answer=(unsigned long long)(MultOne[y]) * (unsigned long long)(MultTwo[z]) + (unsigned long long)MultResult[y+z] + carry;	//Multiplication performed
				MultResult[y+z]=(unsigned long long)(answer % 4294967296llu);	//Store result
				carry=answer /wordLen;		//Calculate carry
			}
			MultResult[y+z]=(unsigned long long)carry;
		}    	
}

//Use of Karatsuba Algorithm [Algorithm 5.2 as per Reference: The Analysis of Algorithms book by Paul Walton Purdom, Jr , Cynthia A]
void FinalMultiplication(unsigned int *NumA, unsigned int *NumB, unsigned int *NumC, unsigned int NumAlen, unsigned int NumBlen, unsigned int *NumClen) {
    //Declare all variables
	unsigned int nDegree, r, s;	
	unsigned int *up1=(unsigned int *)NumA + (nDegree/2), *lw1=(unsigned int *)NumA, *up2=(unsigned int *)NumB + (nDegree/2), *lw2=(unsigned int *)NumB;
	unsigned int *mult_up1_up2 = 0, *mult_lw1_lw2 = 0, *add_up1_lw1 = 0, *add_up2_lw2 = 0, *mult_t1_t2 = 0, *sum_Intermediate = 0, *subt_result = 0, va1 = 0, va2 = 0, va3 = 0, va4 = 0, va5 = 0, va6 = 0, va7 = 0;
	unsigned int *finalAnswer=0;

	add_up1_lw1=(unsigned int *) malloc (((nDegree/2) + 2) * sizeof(unsigned int));	//Initialize memory of size unsigned int
	add_up2_lw2=(unsigned int *) malloc (((nDegree/2) + 2) * sizeof(unsigned int));	//Initialize memory of size unsigned int
	
    if(NumAlen<=35 || NumBlen<=35 || NumAlen<=(NumBlen/2) || NumBlen<=(NumAlen/2)) {	//Need to check wordlength
       multiplyFunc(NumA, NumB, NumC, NumAlen, NumBlen, NumClen);
       return; }	//return result

    if(NumBlen>NumAlen) {
		unsigned int getVar() {
			if(NumAlen>(NumBlen/2))
				return NumAlen;
			else
				return (NumBlen/2);
		}
		nDegree = getVar();
	}
    else {
		unsigned int getVar() {
			if(NumBlen>(NumAlen/2))
				return NumBlen;
			else
				return (NumAlen/2);
		}
		nDegree = getVar();
	}

	//Multiply up1 and lw1 : Step 1 of Algorithm 5.2
    addition(up1, lw1, add_up1_lw1, (NumAlen- (nDegree/2)), (nDegree/2), &va3);      
        
    //Multiply up2 and lw2 : Step 2 of Algorithm 5.2   
    addition(up2, lw2, add_up2_lw2, (NumBlen- (nDegree/2)), (nDegree/2), &va4);

    //Calculate T1*T2: Step 3 of Algorithm 5.2: Multiplication of results from Step 1 and Step 2
    mult_t1_t2=(unsigned int *) malloc ((va3 + va4 + 1) * sizeof(unsigned int));	//Initialize memory of size unsigned int
    FinalMultiplication(add_up1_lw1, add_up2_lw2, mult_t1_t2, va3, va4, &va5);
	
    //Multiply up1 and up2 : Step 4 of Algorithm 5.2
    mult_up1_up2=(unsigned int *) malloc (((NumAlen- (nDegree/2)) + (NumBlen- (nDegree/2))) * sizeof(unsigned int));	//Initialize memory of size unsigned int
    FinalMultiplication(up1, up2, mult_up1_up2, NumAlen- (nDegree/2), NumBlen- (nDegree/2), &va1);
    for (r=(2 * (nDegree/2));r<(2 * (nDegree/2)) + (va1);r++)	//Store result of mult_up1_up2 in NumC
        NumC[r] += mult_up1_up2[r-2 * (nDegree/2)];

    //Multiply lw1 and lw2 : Step 5 of Algorithm 5.2
    mult_lw1_lw2=(unsigned int *) malloc (nDegree * sizeof(unsigned int));	//Initialize memory of size unsigned int
    FinalMultiplication(lw1, lw2, mult_lw1_lw2, (nDegree/2) , (nDegree/2), &va2);
    for (s=0;s<va2;s++)	//Store result of mult_lw1_lw2 in NumC
         NumC[s]+= mult_lw1_lw2[s];
		 
    //Addition of (up1 * lw1) and (up2 * lw2) which will be subtracted from mult_t1_t2
    sum_Intermediate=(unsigned int *) malloc ((va1 + va2) * sizeof(unsigned int));	//Initialize memory of size unsigned int
    addition(mult_up1_up2, mult_lw1_lw2, sum_Intermediate, va1, va2, &va6);

    //Subtract mult_t1_t2 - sum_Intermediate: Step 6 of Algorithm 5.2
    subt_result=(unsigned int *) malloc ((va3 + va4 + nDegree/2) * sizeof(unsigned int));	//Initialize memory of size unsigned int
    memset(subt_result, 0, (va3 + va4 + nDegree/2) * sizeof(unsigned int) * sizeof(unsigned int));	//Need to do memset
    subtraction(mult_t1_t2, sum_Intermediate, (subt_result + (nDegree/2)), va5, va6, &va7);
       
    //Adding for to get the final result
    addition(NumC, subt_result, finalAnswer, NumAlen + NumBlen, (va7 + (nDegree/2)), NumClen);

    free(mult_up1_up2);	//Memory free
    free(mult_lw1_lw2); free(add_up1_lw1);
    free(add_up2_lw2); free(mult_t1_t2);
    free(sum_Intermediate); free(subt_result);
}
 
//Function that will call FinalMultiplication(). Starting point of the program
void Product32(void *a, void *b, void *c, unsigned int NumAlen, unsigned int ba, unsigned int NumBlen, unsigned int bb, unsigned int *NumClen, unsigned int *bc) {
    unsigned int w, i;
    unsigned int *NumA=(unsigned int *) a;	//Cast a, b into short integers of size 32 bits
    unsigned int *NumB=(unsigned int *) b;
    unsigned int *NumC=(unsigned int *) c;

    memset(NumC, 0, (NumAlen + NumBlen) * sizeof(unsigned int));    //Need to do memset of size unsigned int  

	//Final Multiplication
    FinalMultiplication(NumA, NumB, NumC, NumAlen, NumBlen, NumClen);
} 