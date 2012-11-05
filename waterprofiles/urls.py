from django.conf.urls import patterns, include, url

urlpatterns = patterns('waterprofiles.views',
    url(r'^$', 'waterprofiles'),
    url(r'^(?P<waterprofile_id>\d+)/$', 'waterprofile_details'),
)

