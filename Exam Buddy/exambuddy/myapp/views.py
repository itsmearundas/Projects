# views.py
from django.shortcuts import render, redirect
from .models import *

def index(request):
    return render(request,'index.html')



def registration(request):
    from .models import Registration  # Import moved within the function

    if request.POST:
        name = request.POST['name']
        email = request.POST['email']
        phno = request.POST['phno']
        password = request.POST['password']
        
        reg_instance = Registration.objects.create(name=name, email=email, phno=phno, password=password)
        reg_instance.save()

    return render(request, 'registration.html')
