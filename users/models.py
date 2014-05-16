#from django.contrib.auth.models import AbstractBaseUser
from django.db import models
from django.contrib.auth import hashers 
from users.managers import UserManager


class User(models.Model):
    userID = models.AutoField(primary_key=True)
    username = models.CharField(verbose_name='Username',max_length='32',null=False,blank=False,unique=True)
    password = models.CharField(verbose_name='Password',max_length='128',null=False,blank=False)
    email = models.CharField(verbose_name='E-Mail',max_length='255',null=False,blank=False)
    
    '''
    Since we're extending the AbstractBaseUser, a password field is already defined
    password = ...
    '''
    USERNAME_FIELD = 'username'
    REQUIRED_FIELDS = ['username,', 'email','password']
    def __unicode__(self):
        return self.username
    def get_full_name(self):
        return self.username
    def get_short_name(self):
        return self.username
    def has_perm(self, perm, obj=None):
        return True
    def has_module_perms(self, app_label):
        return True
    def is_active(self):
        return True
    def set_password(self, password):
        self.password = hashers.make_password(password)
        
    class Meta:
            db_table = 'Users'
    objects = UserManager();