# models.py
from django.db import models
from django.contrib.auth.models import AbstractUser

class Login(AbstractUser):
    usertype = models.CharField(max_length=40, null=True)
    viewpassword = models.CharField(max_length=40, null=True)

class Registration(models.Model):
    name = models.CharField(max_length=40, null=True)
    email = models.CharField(max_length=40, null=True)
    phno = models.CharField(max_length=40, null=True)
    password = models.CharField(max_length=40, null=True)
   
    user = models.ForeignKey('Login', on_delete=models.CASCADE, null=True)
