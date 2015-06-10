#include <stdio.h>
#include <stdlib.h>
#include <math.h>
void firstlast(int n_global, int p, int *firstrow, int *lastrow);

void CGsetup(int *pjob,        
                int *pn_global,      
                int *firstrow,      
                int *lastrow,       
                int *pp,             
                int *pnnz_local,     
                int *rowptr_local,  
                int *colind_global, 
                double *values,        
                int *plocal_rank,    
                double *b_local)
{
//=================================================================================
// Create a block row of a symmetric positive definite matrix A in CSR format, and
// the corresponding entries in a right hand side vector b. The allocation of all
// arrays is expected to be done by the caller; use the negative values of job to
// get the required sizes to allocate the arrays before calling this with positive
// values of job. Arrays firstrow and lastrow need to be pre-allocated even when 
// job < 0.
//
// All of the input scalar arguments are pointers to a scalar, to allow use from
// C or Fortran without using bind(C). So the first step is to declare regular
// scalars that dereference those pointers, to avoid too many *s littering the
// code.
//
// If anything goes wrong, this prints out a message and returns.
//
//------------
// On entry 
//------------
//   n_global = overall order of the linear system to create; this must be a
//       perfect square as per class discussion on 15 Apr 2014.
//   p  = number of processes
//   local_rank = MPI rank of calling process, starting with 0 and going to p-1
//   job specifies what task and kind of linear system to create:
//       job = 0, nothing is done. This is an error condition.
//       job = 1, populate nnz_local and the arrays for A and b from a discretized 
//           partial differential equation
//       job = 2, set up a diagonal matrix A 
//       job = 3, set up a diagonally dominant matrix A that requires few iterations
//   When job = -k for k = 1, 2, or 3, this function populates the arrays firstrow 
//       and lastrow, both of length p, and the local number of nonzeros
//       required. That info allows the caller to allocate memory for the arrays
//       rowptr_local, colind_global, values, and b_local
//
//------------
// On return
//------------
//   nnz_local = possible overestimate of number of nonzeros the caller needs.
//       The exact number is not known until the PDE discretizer is called, so
//       5*number of locally assigned rows is safe. After this is called with
//       job > 0, the actual number of required nonzeros is correct on return
//   firstrow = array of length p with the indices of the first row assigned to
//       each process
//   lastrow = array of length p with the indices of the last row assigned to
//       each process
//   When job > 0, the arrays rowptr_local, colind_global, values, and b_local
//       are fully populated with the CSR data structure for some sparse matrix
//=================================================================================

    int job = *pjob, n_global = *pn_global, p = *pp, local_rank = *plocal_rank;
    int each, i, j, k, nlocal, meshsize, n;

    //--------------------------------------
    // Basic sanity checks on input values
    //--------------------------------------
    if (p < 1) {
        printf("Bad value in CGsetup; p = %d\n", p);
        return;
    }
    if (n_global < 1) {
        printf("Bad value in CGsetup; n_global = %d\n", n_global);
        return;
    }
    if (local_rank < 0 || local_rank > p-1) {
        printf("Bad value in CGsetup; local_rank = %d\n", local_rank);
        return;
    }
    n = (int)(sqrt((double)n_global)) ;
    if ((abs(job) != 2) && (n*n != n_global)) {
        printf("Bad value in CGsetup; n_global = %d\n", n_global);
        printf("But must be a perfect square for job = %d\n", job);
        return;
    }

    //-----------------------------------------------------------------------------
    // If job = 1 or 3, then the total global number of unknowns corresponds to a
    // (n+2) x (n+2) PDE mesh, where the outter layer of mesh points correspond to
    // Dirichlet boundary conditions. Strictly interior mesh points have five
    // nonzeros per row, lines adjacent to a boundary line have four nonzeros per
    // row, and corners of the mesh of unknowns have 3 nonzeros per row. So for a
    // NxN mesh where N = n+2 the number of unknowns are
    //   interior = (N-4)^2 
    //   side     = 4*(N-4)
    //   corner   = 4
    // and the total number of nonzeros is
    //   nnz = 5*interior + 4*size + 3*corner
    //       = 5*(N-4)^2 + 4*(4*(N-4)) + 3*4
    // However, the slight overestimate 5*(number of local unknowns) is used here
    //-----------------------------------------------------------------------------


    // Set up indices of first and last rows for all processes
    firstlast(n_global, p, firstrow, lastrow);
    nlocal = lastrow[local_rank] - firstrow[local_rank] + 1;

    switch(job){
        case -3:
            *pnnz_local = 5*nlocal;
            break;
        case -2:
            *pnnz_local = nlocal;
            break;
        case -1:
            *pnnz_local = 5*nlocal;
            break;
        case 0:  // Error 
            printf("Bad value in CGsetup; job = %d\n", job);
            break;
        case 1:  // Return regular sparse matrix
            meshsize = n+2; // See fivept.c for why this is done.
            fivept( meshsize,
                    nlocal,
                    n_global,
                    firstrow[local_rank],
                    lastrow[local_rank],
                    pnnz_local,
                    rowptr_local,
                    colind_global,
                    values,
                    b_local);
            break;
        case 2:  // Return diagonal sparse matrix
            for (k = 0; k < nlocal; ++k){
                rowptr_local[k]  = k;
                colind_global[k] = firstrow[local_rank] + k ;
                values[k]        = firstrow[local_rank] + k + 7;
                b_local[k]       = 1.0/(k+1.0);
            }
            rowptr_local[nlocal] = nlocal;
            *pnnz_local = nlocal;
            break;
        case 3: // Return values for matrix with iterations <= 10
            break;
        default:
            printf("Bad value in CGsetup; job = %d\n", job);
            printf("Only abs(job) = 1, 2, or 3 allowed.\n");
            break;
    }

    return;
}

//====================================================================================

void firstlast(int n_global, int p, int *firstrow, int *lastrow)
{
//---------------------------------------------------------------------------------
// Compute the arrays of (globally indexed) first and last row numbers for p
// processes and a linear system of order n_global. This assumes that the rows
// are assigned in order by block to processes. Although no I/O is done the
// iso_fortran_env and formats.f90 are available if debugging or general boredom
// leads to the desire to see WTH is going on. 
//---------------------------------------------------------------------------------
    int each, k;
    each = floor((double) n_global/(double)p);
    firstrow[0] = 0;
    lastrow[0]  = each-1;
    for(k = 1; k < p-1; k++){
        firstrow[k] = firstrow[k-1] + each;
        lastrow[k]  = lastrow[k-1]  + each;
    }
    if (p > 1){
        firstrow[p-1] = lastrow[p-2] + 1;
        lastrow[p-1] = n_global-1;
    }
    return;
}
