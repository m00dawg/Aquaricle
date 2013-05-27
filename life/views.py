from django.shortcuts import render, get_object_or_404
from life.models import Life
from life.models import LifeLog

#def life(request):
#    profiles = WaterProfile.objects.all()
#    return render(request,
#        'waterprofiles.html',
#        {'profiles': profiles,}
#    )

#def waterprofile_details(request, waterprofile_id):
#    profile = get_object_or_404(WaterProfile, pk=waterprofile_id)
#    return render(request,
#        'waterprofile_details.html',
#        {'profile': profile,}
#    )