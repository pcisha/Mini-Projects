ALL: default

MPIR_HOME   = /usr/mpi/gcc/openmpi-1.2.6/bin/
CC          = $(MPIR_HOME)/mpicc
CLINKER     = $(MPIR_HOME)/mpicc
CCC         = $(MPIR_HOME)/mpiCC
CCLINKER    = $(CCC)
F77         = $(MPIR_HOME)/mpif77
F90         = $(MPIR_HOME)/mpif90
FLINKER     = $(MPIR_HOME)/mpif90
OPTFLAGS    = 

### End User configurable options ###

SHELL = /bin/sh

PROFLIB =
CFLAGS  = $(OPTFLAGS) 
CCFLAGS = $(CFLAGS)
FFLAGS = $(OPTFLAGS)
# Use LIBS to add any special libraries for C programs (such as -lm)
LIBS = 
# Use FLIBS to add any special libraries for Fortran programs
FLIBS = 
# Name of your executable goes next:
EXECS = go
OTHEREXECS = 
default: $(EXECS)

# Here is where you put the usual makefile compilation lines.
go: rr.o
	$(CLINKER) $(OPTFLAGS) -o $(EXECS) rr.o $(LIBS)

rr.o: rr.c
	$(CC) -c rr.c

clean:
	rm -rvf go *.o

kleen: clean
	rm -rvf slurmlog
