from django.conf.urls import patterns, include, url

urlpatterns = patterns('life.views',
    url(r'^(?P<life_id>\d+)/$', 'life_details'),
)