ALL: default

MPIR_HOME   = /usr/mpi/gcc/openmpi-1.2.6/bin/
CC          = $(MPIR_HOME)/mpicc
CLINKER     = $(MPIR_HOME)/mpicc
CCC         = $(MPIR_HOME)/mpiCC
CCLINKER    = $(CCC)
F77         = $(MPIR_HOME)/mpif77
F90         = $(MPIR_HOME)/mpif90
FLINKER     = $(MPIR_HOME)/mpif90
OPTFLAGS    =  -Wall -O3
MKLROOT     = /opt/intel/composer_xe_2013.3.163/mkl
### End User configurable options ###

SHELL = /bin/sh

PROFLIB =
CFLAGS  = $(OPTFLAGS) 
CCFLAGS = $(CFLAGS)
FFLAGS = $(OPTFLAGS)

# Use LIBS to add any special libraries for C programs (such as -lm)
LIBS = -lpthread -lm

# Use FLIBS to add any special libraries for Fortran programs
FLIBS = 

# Name of your executable goes next:
EXECS = testcgsetup
OTHEREXECS = 

default: $(EXECS)

# Here is where you put the usual makefile compilation lines.
testcgsetup: main.o CGsetup.o  fivept.o getsten.o coeffs.o
	$(CLINKER) $(OPTFLAGS) -o $(EXECS) main.o CGsetup.o fivept.o getsten.o coeffs.o  $(LIBS)

main.o: main.c CGsetup.h
	$(CC) $(OPTFLAGS) -c main.c

CGsetup: CGsetup.c
	$(CC) $(OPTFLAGS) -c CGsetup.c

fivept.o: fivept.c coeffs.h getsten.h
	$(CC) $(OPTFLAGS) -c fivept.c

coeffs.o: coeffs.c 
	$(CC) $(OPTFLAGS) -c coeffs.c

getsten.o: getsten.c coeffs.h
	$(CC) $(OPTFLAGS) -c getsten.c

clean:
	rm -rvf testcgsetup *.o *~ *#

kleen: clean
	rm -rvf slurmlog result residual *~ 



