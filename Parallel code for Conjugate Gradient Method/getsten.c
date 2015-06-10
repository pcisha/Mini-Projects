#include "coeffs.h"

void getsten(double x, double y, int meshpoints, double stencil[6]){
//----------------------------------------------------------------------------
//
//  For the linear PDE
//     au_xx + bu_yy + cu_z + du_y + eu = f
//  on the unit square,
//  where all of a - f are functions of (x,y), give
//  the values for the stencil oriented in the standard position:
//
//
//	                    st(5)
//	                     |
//	                     |  
//	                     |
//	                     |
//	                     |
//	                     | 
//	  st(2) ----------- st(1) ---------- st(4)
//	                     |
//	                     |
//	                     |
//	                     |
//	                     |
//	                     |
//	                    st(3)
//
//
// stencil(6) is the right hand side vector component from source terms.
// If nonzero Dirichlet boundary conditions are present, they will need
// to be added to the stencil(6) component appropriately by whoever calls
// this subroutine.
//
// This is set on domain [0,meshpoints+1] x [0,meshpoints+1] with mesh 
// spacing h = 1/(meshpoints+1),
// and the functions afun-ffun are presumed to be on [0,1]x[0,1].
// The change of variables is reflected in the a-f computations.
// This is done so that the unknowns are on an meshpointsxmeshpoints grid.
//
//----------------------------------------------------------------------------
    double h;
    double a, b, c, d, e, f;  // function values
    int const center = 0, left = 1, right = 3, down = 2, up = 4, otherside = 5;

    a = afun(x,y);
    b = bfun(x,y);
    c = cfun(x,y);
    d = dfun(x,y);
    e = efun(x,y);
    f = ffun(x,y);

    h = 1.0/(meshpoints+1.0);

    stencil[center]     =  2.0*(a + b) - e*h*h;
    stencil[left]       =  (0.5*d*h - b);
    stencil[down]       =  (0.5*c*h - a);
    stencil[right]      = -(0.5*d*h + b);
    stencil[up]         = -(0.5*c*h + a);
    stencil[otherside]  = -h*h*f;

}

//  vim: set ts=4 sw=4 tw=85 et :
