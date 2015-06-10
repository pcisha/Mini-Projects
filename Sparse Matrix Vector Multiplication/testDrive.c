#include<stdio.h>
#include"spMatVec.h"
#include<stdlib.h>
#include<time.h>

#define SOME_DOUBLE 1000.0

//-------------------------------------------------------------------------
// this function can generate two types of "sparse matrix" using CSR format
// type 0 is diagonal matrix with random value
// type 1 is dense matrix with random value
//-------------------------------------------------------------------------
void generateSpMat(int n,               // order of matrix, n-by-n matrix
                   int* rowptrs,        // "row pointers" of matrix in CSR, length = n+1
                   int* colinds,        // column indices of matrix in CSR, length = nnz
                   double* vals,        // value of entries of matrix in CSR, length = nnz
                   int type,            // 0 for diagonal matrix, 1 for dense
                   int startidx,        // start index
                   int* nnz);           // return from function, nnz 

//--------------------------------------
// write matrix to file A, in COO format
//--------------------------------------
void writeMat(int n,            // order of matrix, n-by-n 
              int nnz,          // number of nonzero
              int* rowptrs,     // "row pointers" of matrix in CSR
              int* colinds,     // column indicies of matrix in CSR
              double* vals,     // value of matrix
              int startidx);    // start index

//------------------------------
// write vector (x or y) to file
//------------------------------
void writeVec(const char* name, // name of file
              double* vec,      // vector contains value of vector
              int length);      // length of vector

int main() {
    //----------------------------
    // set random number generator
    //----------------------------
    srand(time(NULL));

    
    //--------------------------
    // parameters you can change
    //--------------------------
    int n = 1000;       // matrix size
    int startidx = 0;   // 0 or 1
    int type = 0;       // 0 for diagonal, 1 for dense

    int nnz;

    //-----------------------
    // allocate ENOUGH memory
    //-----------------------
    int* rowptrs = (int*) malloc(sizeof(int)*(n+1));
    int* colinds = (int*) malloc(sizeof(int)*n*n);
    double* vals = (double*) malloc(sizeof(double)*n*n);
    double* y = (double*) malloc(sizeof(double)*n);
    double* y_trans = (double*) malloc(sizeof(double)*n);
    double* x = (double*) malloc(sizeof(double)*n);

    //-------------
    // initialize x
    //-------------
    int i;
    for(i=0;i<n;i++) {
        x[i] = rand()/SOME_DOUBLE;
    }

    //----------------
    // generate matrix
    //----------------
    generateSpMat(n, rowptrs, colinds, vals, type, startidx, &nnz);

    //----------------------
    // call spMatVec y = A*x
    //----------------------
    spMatVec(n, nnz, rowptrs, colinds, vals, x, y, startidx);

    //--------------------------------------
    // call spTransMatVec y = transpose(A)*x
    //--------------------------------------
    spTransMatVec(n, nnz, rowptrs, colinds, vals, x, y_trans, startidx);

    //---------------------------------------
    // write result to files A, y, y_trans, x
    //---------------------------------------
    writeMat(n, nnz, rowptrs, colinds, vals, startidx);
    writeVec("x", x, n);
    writeVec("y", y, n);
    writeVec("y_trans", y_trans, n);

    //-------------
    // free memory
    //-------------
    free(rowptrs);
    free(colinds);
    free(vals);
    free(y);
    free(y_trans);
    free(x);

    return 0;
}

void generateSpMat(int n, int* rowptrs, int* colinds, double* vals, int type, int startidx, int* nnz) {
    // temp variables
    int i,j;
    switch(type) {
        //----------------
        // diagonal matrix
        //----------------
        case 0:
            *nnz = n;
            //----------------------
            // put values in rowptrs
            //----------------------
            for(i=0;i<n+1;i++) {
                rowptrs[i] = i+startidx;
            }
            //-------------------------------
            // put values in colinds and vals
            //-------------------------------
            for(i=0;i<n;i++) {
                colinds[i] = i+startidx;
                vals[i] = rand()/SOME_DOUBLE;
            }
            break;
        //-------------
        // dense matrix
        //-------------
        case 1:
            *nnz = n*n;
            //----------------------
            // put values in rowptrs
            //----------------------
            for(i=0;i<n+1;i++) {
                rowptrs[i] = i*n+startidx;
            }
            //-------------------------------
            // put values in colinds and vals
            //-------------------------------
            for(i=0;i<n;i++) {
                for(j=0;j<n;j++) {
                    colinds[i*n+j] = j+startidx;
                    vals[i*n+j] = rand()/SOME_DOUBLE;
                }
            }
            break;
        default:
            printf("Error: can not generate type of matrix specified by type: %d\n", type);
            abort();
            break;
    }
}

void writeMat(int n, int nnz, int* rowptrs, int* colinds, double* vals, int startidx) {
    //------------
    // open a file
    //------------
    FILE* fileA = fopen("A", "w");
    if(fileA==NULL) {
        printf("Error: can not open file 'A'\n");
        abort();
    }
    int i, j;
    //---------------------
    // write matrix to file
    //---------------------
    for(i=0;i<n;i++) {
        for(j=rowptrs[i]-startidx;j<rowptrs[i+1]-startidx;j++) {
            fprintf(fileA, "%d, %d, %.16e\n", i+1, colinds[j]-startidx+1, vals[j]);
        }
    }

    //-----------
    // close file
    //-----------
    fclose(fileA);
}

void writeVec(const char* name, double* vec, int length) {
    //------------
    // open a file
    //------------
    FILE* pFile = fopen(name, "w");
    if(pFile==NULL) {
        printf("Error: can not open file %s\n", name);
        abort();
    }
    
    //---------------------
    // write vector to file
    //---------------------
    int i;
    for(i=0;i<length;i++) {
        fprintf(pFile, "%.16e\n", vec[i]);
    }

    //-----------
    // close file
    //-----------
    fclose(pFile);
}

