from django.contrib.auth.models import AbstractBaseUser
from django.db import models
from users.managers import UserManager


class User(AbstractBaseUser):
    userID = models.AutoField(primary_key=True)
    username = models.CharField(verbose_name='Username',max_length='32',null=False,blank=False,unique=True)
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
        return true
    class Meta:
            db_table = 'Users'
    objects = UserManager();