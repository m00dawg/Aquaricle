from django.shortcuts import render, get_object_or_404, redirect
from django.utils import timezone
from django.core.urlresolvers import reverse
from django.contrib.auth import authenticate, login, logout

from users.models import AquaricleUser, AquaricleUserLoginForm
from users.admin import UserCreationForm


def user_login(request):
    user = AquaricleUser()
    status = None
    
    if request.method == 'POST':
        username = request.POST['username']
        password = request.POST['password']
        user = authenticate(username=username, password=password)
        if(user is not None):
            if user.is_active:
                login(request, user)
                return redirect('/aquariums')
            else:
                status = "User Is Not Active"
        else:
            status = "Blown Up"
                    
    user_login_form = AquaricleUserLoginForm(instance=user)
    return render(request, 
        'login.html',
        {'user_login_form' : user_login_form,
         'status' : status})
        
def user_logout(request):
    logout(request)
    return redirect('/users/login')