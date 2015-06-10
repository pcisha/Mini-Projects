/* CSCI - B673
 * Name: ex2: HW2 Sparse Matrix Vector Multiply
 * Filename: spMatVec.c
 * Author: Prachi Shah
 * Year: 2014 */

#include<stdio.h>
#include"spMatVec.h"

//Multiplying a sparse matrix
void spMatVec(int n, int nnz, int* rowptrs, int* colinds, double* vals, double* x, double* y, int startidx) {
	int j, k;
	
	for(j=startidx; j<n; j++) {							//for j = 1 to n
	y[j] = 0;									//Initialize y(1:n) = 0
		for(k = rowptrs[j]; k<= rowptrs[j+1]-1; k++) {				//for k = row(j) to row(j+1)-1
		   y[j] = y[j] + vals[k] * x[colinds[k]];				//y(j) = y(j) + val(k)*x(col(k))
	   }										//end for
	}										//end for	
}

//Perform transposed-matrix vector product
void spTransMatVec(int n, int nnz, int* rowptrs, int* colinds, double* vals, double* x, double* y, int startidx) {
	int i, j, k;
    	for(i=0; i<n; i++) {								//Initialize y(1:n) = 0
		y[i] = 0;
	}
	
	for(j=startidx; j<n; j++) {							//for j = 1 to n
		for(k = rowptrs[j]; k<=rowptrs[j+1]-1; k++) {				//for k = row(j) to row(j+1)-1
			if(vals[k] == 0)
				printf("Warning: Non-zero values are allowed!");
		   y[colinds[k]] = y[colinds[k]] + vals[k] * x[j];			//y(col(k)) = y(col(k)) + val(k)*x(j)
	   	}									//end for
	}										//end for
}
