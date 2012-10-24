from django.conf.urls import patterns, include, url

urlpatterns = patterns('aquarilog.views',
    url(r'^$', 'index'),
    url(r'^aquarium/(?P<aquarium_id>\d+)/$', 'aquarium', name='aquarium'),
)
