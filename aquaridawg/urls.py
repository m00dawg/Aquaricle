from django.conf.urls import patterns, include, url

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
    url(r'^$', 'aquaridawg.views.index', name='index'),
    url(r'^aquarilog/', include('aquarilog.urls')),
    url(r'^admin/', include(admin.site.urls)),
)
