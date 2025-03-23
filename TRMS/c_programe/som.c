#include<stdio.h>


void increment(int x[], int n){
	int i;
	for(i=0; i<n; i++)
		x[i] += x[i] *.1;
}
int main(void){
	int a[10], i, n;
	printf(“N ?= “);
	scanf(“%d”, &n);
	for (i = 0; i < n; i++){
		printf(“Element %d”, i+1);
		scanf(“%d”, &a[i]);
	}
    increment(a,n);	
    for (i = 0; i < n; i++)
		printf(“%d\t”, a[i]);
    return 0;
}
