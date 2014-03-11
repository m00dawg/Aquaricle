from django.db import models
from django.db import connection
from django.contrib.auth.models import BaseUserManager

class UserManager(BaseUserManager):
    def create_user(self, email, password=None):
        if not email:
            raise ValueError('A valid e-mail address is required')
        user = self.model(email=self.normalize_email(email))
        user.set_password(password)
        user.save(user=self._db)
    def create_superuser(self, email, password=None):
        return self.create_user(email, password)