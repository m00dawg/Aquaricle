from django.conf.urls import patterns, include, url

urlpatterns = patterns('life.views',
    url(r'^(?P<life_id>\d+)/$', 'life_details'),
    url(r'^aquarium/(?P<aquarium_id>\d+)/$', 'life_list'),
    url(r'^add/(?P<aquarium_id>\d+)/$', 'add_life'),
    url(r'^(?P<life_id>\d+)/edit$', 'edit_life'),
)