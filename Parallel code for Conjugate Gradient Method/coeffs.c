#include <math.h>
//--------------------------------------------------------------------------------------
// Coefficient and boundary condition functions that define the general 2D linear PDE:
//
//     au_xx + bu_yy + cu_x + du_y + eu = f,
//     subject to u = BC on the boundaries
//
// The boundary conditions are Dirichlet. All of a-f can be functions of x and y, but
// the values used here correspond to the function derived in class. So the solution
// should be the same as the boundary conditions ... unless I have miscalculated.
// For a symmetric positive definite matrix, the PDE needs to be of the form
//
//    (Au_x)_x + (Bu_y)_y +  eu = f, 
//
// where A > 0 and B > 0 on the entire domain. In that case, the a-f functions need
// to be 
//     a = A
//     b = B
//     c = A_x
//     d = B_y
//     e  is unconstrained
//     f  is unconstrained
//
// So make sure that the c and d functions are the corresponding derivatives of A 
// and B.
//--------------------------------------------------------------------------------------

double afun(double x, double y)
{
    double a;
    // a =  1.0  + x;
    a =  1.0;
    return a;
} 

double  bfun(double x, double y) 
{
    double b;
    // b = 2.0 + sin(y);
    b = 1.0 ;
    return b;
} 

double  cfun(double x, double y) 
{
    double c;
    // c = 1.0;
    c = 0.0;
    return c;
} 

double  dfun(double x, double y)
{
    double d;
    // d =  cos(y);
    d =  0.0;
    return d;
} 

double  efun(double x, double y) 
{
    double e;
    e = 0.0;
    return e;
} 

double  ffun(double x, double y) 
{
    double f;
    f = -(x*x*x*x) + 3.0*x*x + (8.0 + 6.0*y - y*y)*x - 4.0*y + 2.0;
    return f;
} 

double BC(double x, double y) 
{
    double boco;
    double const pi = 3.14159265, multiplier = 4.0;
    boco = sin(multiplier*x*pi) + sin(multiplier*y*pi);;
    return boco ;
} 
    
//  vim: set ts=4 sw=4 tw=85 et :
