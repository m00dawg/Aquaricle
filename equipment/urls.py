from django.conf.urls import patterns, include, url

urlpatterns = patterns('equipment.views',
#    url(r'^$', 'index'),
    url(r'^(?P<equipment_id>\d+)/$', 'equipment_details'),
    url(r'^aquarium/(?P<aquarium_id>\d+)/$', 'equipment_list'),
)