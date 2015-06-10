LD=gcc
CC=gcc

all: spMatVec.o testDrive.o
	$(LD) -o matvec testDrive.o spMatVec.o 

spMatVec.o: spMatVec.h spMatVec.c
	$(CC) -c spMatVec.c

testDrive.o: testDrive.c
	$(CC) -c testDrive.c

clean:
	rm -rvf *.o 

kleen: clean
	rm -rvf matvec A y y_trans x
