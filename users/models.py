#from django.contrib.auth.models import AbstractBaseUser
from django.db import models
from django.contrib.auth import hashers 
from django.forms import ModelForm, PasswordInput, CharField
from django.contrib.auth.models import BaseUserManager

class AquaricleUserManager(BaseUserManager):
    def create_user(self, email, password=None):
        if not email:
            raise ValueError('A valid e-mail address is required')
        user = self.model(email=self.normalize_email(email))
        user.set_password(password)
        user.save(user=self._db)
    def create_superuser(self, email, password=None):
        return self.create_user(email, password)

class AquaricleUser(models.Model):
    userID = models.AutoField(primary_key=True)
    username = models.CharField(verbose_name='Username',max_length='32',null=False,blank=False,unique=True)
    password = models.CharField(verbose_name='Password',max_length='128',null=False,blank=False)
    email = models.EmailField(verbose_name='E-Mail',max_length='255',null=False,blank=False)
    
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
    def is_authenticated(self):
        return True
    def check_password(self, password, encoded):
        return hashers.check_password(password, encoded)
    def set_password(self, password):
        self.password = hashers.make_password(password)
        
    class Meta:
            db_table = 'Users'
    objects = AquaricleUserManager();
    
class AquaricleUserLoginForm(ModelForm):
    class Meta:
        model = AquaricleUser
        password = CharField(widget=PasswordInput)
        fields = ('username', 'password')
        widgets = {
            'password': PasswordInput()
        }
        
#class UserBackend(object):
#    def authenticate(self, username=None, password=None)