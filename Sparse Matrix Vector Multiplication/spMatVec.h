#ifndef _SP_MAT_VEC__H__
#define _SP_MAT_VEC__H__

//---------------------------------------------------
// sparse matrix vector multiply: 
//              y = A * x
// A is in CSR (Compressed Sparse Row) data structure
// y and x are 1D dense array
//---------------------------------------------------
void spMatVec(int n,            // order of A, n-by-n matrix
              int nnz,          // number of nonzeros
              int* rowptrs,     // "row pointers" for A in CSR format
              int* colinds,     // column indices for A in CSR format
              double* vals,     // value of entries of A in CSR format
              double* x,        // 1D dense array x 
              double* y,        // 1D dense array y
              int startidx);    // 0 or 1

//---------------------------------------------------
// sparse matrix vector multiply: 
//              y = transpose(A) * x
// A is in CSR (Compressed Sparse Row) data structure
// y and x are 1D dense array
//---------------------------------------------------
void spTransMatVec(int n,            // order of A, n-by-n matrix
                   int nnz,          // number of nonzeros
                   int* rowptrs,     // "row pointers" for A in CSR format
                   int* colinds,     // column indices for A in CSR format
                   double* vals,     // value of entries of A in CSR format
                   double* x,        // 1D dense array x 
                   double* y,        // 1D dense array y
                   int startidx);    // 0 or 1

#endif
