README

--ABOUT--
		DATE: 09/12/2013
		COURSE: CSCI-P538
		YEAR: Fall 2013
		DEVELOPERS: Prachi Shah(pracshah), Oliver Lewis(lewiso)
		PROJECT: Lab 1: C Programming Primer and Parsing Network Packets
		SECTION: Packet Parsing
--CONFIGURATION--
		OPERATING SYSTEM: Ubuntu 12.04 or higher
		RAM SIZE: 2 GB or higher
		PROCESSOR SPEED: 2GHZ or higher 
--SOFTWARES USED--
		COMPILER: GNU C compiler
		TEXT EDITOR: gedit 
--FILE LISTINGS--
		parse headers.c, makefile2, README2

--DESCRIPTION OF CODE--
		> The projects will help us understand the Ethernet, TCP(Transmission Control Protocol), IP(Internet Protocol), TLS(Transport Layer Security) and 			  SSL(Secure Socket Layer) headers that are a part of the data packet.
		> The project will make one familiar with data packet parsing.
		> You will be able to parse the data packet and read the Ethernet header, IP header, TCP header and SSL header information.
		> Topics covered in this section are: decimal to binary conversion, decimal to hexadecimal conversion , reading packet headers.
		> Four standard libraries named stdio.h, stdlib.h, string.h and pcap.h are used in the programs.
		
		SECTION 2.1: Packet Parsing: [parse_headers.c]
		1] Each header information is stored in  a structure.
		2] Every structure is assigned a pointer of data type (struct).
		3] Ethernet header data capture: The MAC source and destination addresses are printed.
		   > The source and destination MAC addresses are fetched from the packets and the address is converted into Hexadecimal values.
		4] IP header data capture: The IP source and destination addresses are printed.
		   > The source and destination IP addresses are fetched from the packets and the address is of data type integer.
		5] TCP header data capture: The TCP source and destination port numbers are printed.
		   > The source and destination port numbers are fetched from the packets.
		   >The port number is of type decimal, which is converted into binary format and the resulting value is printed as an integer data type value.
		6] SSL header data capture: To check all packets for TLS protocol use.
		   > Every data packet's version is checked to detect the value '03' and '01'. If the version contains these values then the packet is using TLS 			     protocol.
		   
--COMPILING THE PROGRAMS-- 

gcc -g parse_headers.c -lpcap -o parse_headers.out

		 The program will be compiled in Linux operating system after installing the GNU C compiler software from Linux's Software Center.
		 
		 > For compiling the parse_headers.c file, write the following in the Linux bash:
		 bash> gcc -g parse_headers.c -lpcap -o parse_headers.out
		 > The -l flag is used to tell the compiler that you need to dynamically link the libpcap library. This library is used for packet capturing. 
		 The .out file is created once the parse_headers.c file is successfully compiled.
		 > If libpcap library isn't installed, write the following in the Linux bash:
		 bash> sudo apt-get install libpcap-dev	 
		 
--RUNNING THE PROGRAMS--
		 > For executing the parse_headers.c file, write the following in the Linux bash:
		 bash> ./parse_headers.out capture1.pcap
		 > Hers, capture1.pcap file is an argument taken by the program to execute. 
		 > A specific output will be displayed in the bash window itself of the format:
		-------- Packet 34 ------------
		Size: 235 bytes
		MAC src: 5c:5e:ab:25:58:a6
		MAC dest: 00:24:e8:2f:c8:0f
		IP src: 74.125.130.84
		IP dest: 129.79.242.44
		Src port: 443
		Dst port: 43416
		TLS 1.0: Yes
		
--CREDITS--
		1) Bit Fields / Flags Tutorial with Example:
		http://forum.codecall.net/topic/56591-bit-fields-flags-tutorial-with-example/

		2) Conversion Table - Decimal, Hexadecimal, Octal, Binary
		http://ascii.cl/conversion.htm

		3) Scan Conversion Specifiers
		http://publib.boulder.ibm.com/infocenter/comphelp/v7v91/index.jsp?topic=%2Fcom.ibm.vacpp7a.doc%2Fstandlib%2Fref%2Fscanconversionspecifiers.htm

		4) Internet Protocols
		http://docwiki.cisco.com/wiki/Internet_Protocols

		5) TCP/IP Reference
		http://nmap.org/book/tcpip-ref.html

		6) htons(), htonl(), ntohs(), ntohl()
		http://www.beej.us/guide/bgnet/output/html/multipage/htonsman.html

		7) README
		http://en.wikipedia.org/wiki/README

		8) Makefiles
		http://mrbook.org/tutorials/make/















