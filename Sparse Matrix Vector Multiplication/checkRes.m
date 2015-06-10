% ----------------------------------------------------------------------------
% check result of sparse matrix vector multiply
% Assume there are files called 'A', 'y', 'y_trans', 'x' in current directory
% ============================================================
% Weiran (Ryan) Zhao 
% Started: Fri,Mar 28th 2014 01:54:03 PM EDT
% Last Modified: Fri,Mar 28th 2014 09:11:59 PM EDT
% ----------------------------------------------------------------------------

%------------
% load files
%------------
A = load('A');
A = sparse(A(:,1), A(:,2), A(:,3));
x = load('x');
y = load('y');
y_trans = load('y_trans');

%---------------------------------------------------
% given A and x, use matlab to calculate the result
%---------------------------------------------------
y_matlab = A*x;
y_trans_matlab = A'*x;

%--------------------------------------------------------------------------
% check SSD (sum squared difference) between your result and matlab result
%--------------------------------------------------------------------------
ssd_y = (y-y_matlab)'*(y-y_matlab);
fprintf('SSD error between your result and matlab result for y: %.6e\n', ssd_y);

ssd_ytrans = (y_trans-y_trans_matlab)'*(y_trans-y_trans_matlab);
fprintf('SSD error between your result and matlab result for y_trans: %.6e\n', ssd_ytrans);
