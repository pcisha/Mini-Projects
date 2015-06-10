#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include "coeffs.h"
#include "getsten.h"

void fivept(int meshpoints , 
            int nrows    , 
            int ncols    , 
            int firstrow , 
            int lastrow  , 
            int *pnnz      , 
            int *rows     , 
            int *cols     , 
            double *vals ,
            double *rhs) {
//--------------------------------------------------------------------------------------
// Set up the COO data structure for a block row of a five-point centered diff matrix.
// This is for a square meshpoints x meshpoints grid, with the outter layer of
// meshpoints corresponding to boundary values. In detail, consider a m x m mesh of 
// nodes on the unit square [0,1]x[0,1] in 2D physical space:
// 
//     B   B   B   B   B   B   B   B   B <--- this corner is at (1,1)
//     B   u   u   u   u   u   u   u   B
//     B   u   u   u   u   u   u   u   B
//     B   u   u   u   u   u   u   u   B
//     B   u   u   u   u   u   u   u   B
//     B   u   u   u   u   u   u   u   B
//     B   u   u   u   u   u   u   u   B
//     B   u   u   u   u   u   u   u   B
//     B   B   B   B   B   B   B   B   B
//     ^
//     |
//     this corner is at (0,0)
// 
// Here the mesh size has m = 9, wich corresponds to a mesh parameter of h = 1/(m-1).
// Points marked with B are boundary nodes, assumed to be specified as Dirichlet
// boundary values. The interior nodes marked with "u" are the unknown values of the
// function u(x,y), so the total number of unknowns is (m-2)^2. That means a sparse
// linear system is created of order n = (m-2)^2.
// 
// The number of nonzeros in the sparse matrix is bounded above by 5*n, since each
// unknown has at most 5 nonzeros in its row of the matrix. More precisely, during
// the discretization using 5-point centered differences, each unknown has 0, 1, or 
// 2 adjacent boundary values. Those are shown using I, S, or C below:
// 
//     B   B   B   B   B   B   B   B   B
//     B   C   S   S   S   S   S   C   B
//     B   S   I   I   I   I   I   S   B
//     B   S   I   I   I   I   I   S   B
//     B   S   I   I   I   I   I   S   B
//     B   S   I   I   I   I   I   S   B
//     B   S   I   I   I   I   I   S   B
//     B   C   S   S   S   S   S   C   B
//     B   B   B   B   B   B   B   B   B
// 
// Mnemonic: I = "interior", S = "side", and C = "corner". Using absolute value 
// signs for cardinality, the numbers of each type of unknown are
//
//    |I| = (m-4)^2
//    |S| = 4*(m-4)
//    |C| = 4
// 
// Multiplying by the corresponding number of adjacent unknowns gives
//
//     nnz = 5*|I|      + 4*|S|       + 3*|C|
//         = 5*(m-4)^2  + 4*(4*(m-4)) + 3*4
//         = [5*(m-4) + 6]*[m - 4 + 2]   (by factoring 5x^2 + 16x +12)
//         = (5m-14)*(m-2)
//         = 5m^2 - 24m + 28
// 
// The only requirement is that m = #meshpoints > 2. This yields a sparse matrix of
// order 49, each row corresponding to one unknown in the physical mesh. This
// discretizer is for a parallel code with the sparse matrix partitioned by block
// rows. Each block row consists of row numbers from firstrow to lastrow, inclusive.
// The row numbers are indexed starting from 0. Some complexity comes from having a
// block row being able to be started/ended anywhere in the lines of physical mesh
// points.
//
//------------
// Variables:
//------------
// meshpoints = number of meshpoints in x and y directions, including boundary nodes
// nrows      = number of rows in specified block row
// ncols      = total number of unknowns in overall system; since partitioning is by
//               block rows, this is also the local number of columns.
// firstrow   = (global) row number of the first row in the block row
// lastrow    = (global) row number of the last row in the block row
// pnnz       = (local) number of nonzeros in the given block row
// rows       = (locally indexed) row "pointers" of block row's CSR data structure 
// cols       = (globally indexed) column indices of block row's CSR data structure 
// vals       = (local) nonzero values in block row's CSR data structure 
// rhs        = (local) part of right hand side vector corresponding to the block row
//
// nxoff = distance in matrix rows between two nodes in the x direction
// nyoff = distance in matrix rows between two nodes in the y direction
// node  = node (unknown) number, locally numbered from 0
// nodeg = node (unknown) number in the global matrix
//
// This calls getsten(), which returns the 5-pt stencil for a given (x,y) point in
// the domain. Because the mesh nodes assigned to a block row can being or end in
// the middle of an x or y meshline, a go to is used to bail out from the loops.
//--------------------------------------------------------------------------------------

    // meshpoints counts both boundary edges, so nglobal = (meshpoints-2)^2
    int n;
    int nnz = *pnnz;

    // Local variables
    double h;
    int benoisy = 0;  // Massive debug output to stderr
    // Indices for stencil directions
    int center = 0, left = 1, right = 3, down = 2, up = 4, otherside = 5;
    int node, nodeg, j, nxoff, nyoff, m;
    int ixstart, iystart;
    int ix, iy;

    double const ONE = 1.0, ZERO = 0.0;
    double x, y, stencil[6];

    // Start of executable statements
    m     = meshpoints-2;
    n     = m*m;                 // global number of unknowns
    nrows = lastrow-firstrow+1;  // local number of unknowns
    ncols = n;                   // global, since pttn is by block rows
    node  = 0;                   // counter for variable number (1:n_local)
    nodeg = firstrow;            // counter for global variable number (firstrow:lastrow)
    j     = 0 ;                  // counter for nonzeros in matrix (1:nnz)

    nxoff = 1;                   // offset between consecutive x values in the matrix
    nyoff = m ;                  // offset between consecutive y values in the matrix
    rows[node] = 0;              // rowptrs always starts from 1 (0 in C)

    h = 1.0/(meshpoints-1.0);    // = 1/(m+1), mesh spacing

    if (benoisy > 0) {
        printf("\n---------------------");
        printf("\nfivept has meshpoints = %d", meshpoints);
        printf("\nfivept has m = %d", m);
        printf("\nfivept has nrows = %d", nrows);
        printf("\nfivept has n = %d", n);
        printf("\nfivept has ncols = %d", ncols);
        printf("\nfivept has nnz = %d", nnz);
        printf("\n---------------------");
        printf("\nfivept has nyoff = %d", nyoff);
        printf("\nfivept has firstrow = %d", firstrow);
        printf("\nfivept has lastrow = %d", lastrow);
    }

    // Compute block row of matrix A and corresponding right hand side rhs:
    
    iystart = firstrow/m; // Must be int divide here
    ixstart = firstrow%m; // In case start in middle of a y-line
    if (benoisy > 0) {
        printf(" \nfivept has iystart = %d", iystart);
        printf(" \nfivept has ixstart = %d", ixstart);
        printf(" \n---------------------");
    }

    // Loop over mesh points, x varying fastest:
    for(iy = iystart; iy < m; ++iy) {
        y = (1.0+(double)iy)*h;
        if (iy > iystart) ixstart = 0;  // After first y-line, x starts at beginning
        for(ix = ixstart; ix < m; ++ix) {
            x = (1.0+(double)ix)*h;
            getsten(x, y, m, stencil);
            rhs[node]  = stencil[otherside];
            
            // Away from bottom boundary?
            if (iy > 0) {
                cols[j] = nodeg - nyoff;
                vals[j] = stencil[down];
                j++; }
            else
                rhs[node] -= stencil[down]*BC(ZERO,y-h);

            // Away from left hand boundary?
            if (ix > 0) {
                cols[j] = nodeg - nxoff;
                vals[j] = stencil[left];
                j++; }
            else
                rhs[node] -= stencil[left]*BC(x-h,ZERO);

            // Central node in stencil = diagonal element of A
            cols[j] = nodeg;
            vals[j] = stencil[center];
            j++;

            // Away from right hand boundary?
            if (ix < m-1) {
                cols[j] = nodeg + nxoff;
                vals[j] = stencil[right];
                j++; }
            else
                rhs[node] -= stencil[right]*BC(x+h,ONE);

            // Away from top boundary?
            if (iy < m-1) {
                cols[j] = nodeg + nyoff;
                vals[j] = stencil[up];
                j++; }
            else
                rhs[node] -=  stencil[up]*BC(ONE,y+h);

            // Increment variable counters:
            node++;
            nodeg++;
            rows[node] = j;

            if (nodeg > lastrow) goto bailout;

        } // end xaxis (ix loop)
    } // end yaxis (iy loop)

bailout:
    
    // Total number of unknowns for this row
    n = node;
    if (benoisy > 0 ) {printf( "\nend of fivept has n = %d", n);}

    // Total number of nonzeros in coefficient matrix:
    *pnnz = j;
    if (benoisy > 0 ) {printf( "\nend of fivept has nnz = %d\n", *pnnz);}
    return;
}

//  vim: set ts=4 sw=4 tw=85 et :
