from django.conf.urls import patterns, include, url

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    # Examples:
#    url(r'^$', 'aquaridawg.views.home', name='home'),
    url(r'^$', 'aquaridawg.views.index', name='index'),
    url(r'^aquarium/(?P<aquarium_id>\d+)/$', 'aquaridawg.views.aquarium', name='aquarium'),
    # url(r'^aquaridawg/', include('aquaridawg.foo.urls')),

    # Uncomment the admin/doc line below to enable admin documentation:
    # url(r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
    url(r'^admin/', include(admin.site.urls)),
)
