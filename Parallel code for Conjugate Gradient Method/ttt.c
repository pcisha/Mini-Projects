#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include "CGsetup.h"
#define pmax 12
#define nmax 345
#define nnzmax (7*nmax)

int main(void)
{
//------------------------------------------------------------------------------
// A short test driver for the CGsetup routine. This prints out just about
// everything, so keep the sizes small. For testing, (re)set the values of
// local_rank, n_global, and p, and then recompile. All output is to stderr and
// stdout. The n_global must be a perfect square for the PDE problem. The arrays
// defining the CSR data structure are written out in a format that allows them 
// to be plucked out and put into a Matlab script for validation.
//------------------------------------------------------------------------------

    int job, n_global, p, local_rank;
    int nnz_local;
    int firstrow[pmax];
    int lastrow[pmax];
    int *rowptr_local;
    int *colind_global;
    double *values;
    double *b_local;

    int each, i, j, k, allocstatus, nlocal;

    local_rank = 0;  // MPI rank, starting from 0. Must be < p below
    n_global   = 25; // number of unknowns; must be perfect square for 5-pt
    p          = 3;

    //-------------- First call to get sizes of arrays ------------------------
    job        = -1;  // For 5-pt centered diff operator on unit square
    CGsetup(&job, &n_global, firstrow, lastrow, &p, &nnz_local, 
            rowptr_local, colind_global, values, &local_rank, b_local);

    printf("====================== After first call, got: ======================\n");
    printf("local_rank = %d\n", local_rank);

    printf("firstrow = \n");
    for (i = 0; i < p; ++i) printf("%d\n", firstrow[i]);

    printf("lastrow = \n");
    for (i = 0; i < p; ++i) printf("%d\n", lastrow[i]);

    printf("rows/proc = \n");
    for (i = 0; i < p; ++i) printf("%d\n", 1+lastrow[i]-firstrow[i]);

    nlocal = lastrow[local_rank] - firstrow[local_rank] + 1;
    printf("nlocal = %d\n", nlocal);

    // Got sizes; use them to allocate arrays
    rowptr_local  = (int *) malloc((nlocal+1)*sizeof(int));
    colind_global = (int *) malloc((nnz_local)*sizeof(int));
    values        = (double *) malloc((nnz_local)*sizeof(double));
    b_local       = (double *) malloc((nlocal)*sizeof(double));

    //-------------- Second call to get actual array data ------------------------
    job = 1;
    CGsetup(&job, &n_global, firstrow, lastrow, &p, &nnz_local, 
            rowptr_local, colind_global, values, &local_rank, b_local);
    
    printf("====================== After second call, got: ======================\n");
    printf("rowptrs = [...\n");
    for (i = 0; i <= nlocal; ++i) printf("%d\n", rowptr_local[i]);
    printf("];\n");

    printf("colinds = [...\n");
    for (i = 0; i < nnz_local; ++i) printf("%d\n", colind_global[i]);
    printf("];\n");

    printf("vals = [...\n");
    for (i = 0; i < nnz_local; ++i) printf("%g\n", values[i]);
    printf("];\n");

    printf("b = [...\n");
    for (i = 0; i < nlocal; ++i) printf("%g\n", b_local[i]);
    printf("];\n");

    return 0;

}
