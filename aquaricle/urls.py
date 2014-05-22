from django.conf.urls import patterns, include, url

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    url(r'^$', 'aquaricle.views.index', name='index'),
    url(r'^users/', include('users.urls')),
    url(r'^waterlogs/', include('waterlogs.urls')),
    url(r'^aquariums/', include('aquariums.urls')),
    url(r'^equipment/', include('equipment.urls')),
    url(r'^life/', include('life.urls')),
    url(r'^admin/', include(admin.site.urls)),
)
