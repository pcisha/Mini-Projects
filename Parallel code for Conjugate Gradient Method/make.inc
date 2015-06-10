#===== Compilers =================================================================

CC  = gcc
F90 = ifort
# F90 = gfortran  
F95 = $(F90)


#===== Optimizations =============================================================
# Some of these are tied to the compiler being Intel. Big fat hairy deal, heh?

CCOPTS = -O3
OPTS = -O3
# OPTS = -O3 -integer-size 64 

# OPTS = -O3 \
#     -opt-prefetch   \
#     -align all      \
#     -ccdefault none \
#     -ftz            \
#     -funroll-loops  \
#     -pad            \
#     -falign-functions=16 \
#     -fp-model fast=2 \
#     -fp-speculation=fast \
#     -opt-prefetch \
#     -no-prec-div \
#     -xHost \
#     -vec-report6


    # use -xAVX in behemoth, or other systems where the cpu supports
    # it. I.e., AVX > SSE4.2. When compiling on the machine to execute
    # the code, -xHost should take care of that.

    # -check arg_temp_created   # Can be much slower!
    # -warn all \

#===== Libraries and Other Effluvia ==============================================

# Use the 2013 Intel libs:
# MKLROOT = /opt/intel/composer_xe_2013.3.163/mkl
# LIBS =   -L$(MKLROOT)/lib/intel64 -lmkl_rt -lpthread -lm
LIBS =   -lm

# For GNU gfortran and GSL: 
# LIBS = -L/usr/lib/ -lgsl -lblas

