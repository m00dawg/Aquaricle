from django.shortcuts import render, get_object_or_404
from waterprofiles.models import WaterProfile

def waterprofiles(request):
    profiles = WaterProfile.objects.all()
    return render(request,
        'waterprofiles.html',
        {'profiles': profiles,}
    )

'''
def aquarium_details(request, aquarium_id):
        aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
        equipment = Equipment.objects.get_maintenance(aquarium_id)
        latest_water_logs = \
            WaterLog.objects.filter(aquariumID = aquarium_id) \
                .order_by('-testedOn')[:10]

        return render(request,
            'aquarium_details.html',
            {'aquarium': aquarium,
             'equipment' : equipment,
             'latest_water_logs': latest_water_logs,}
        )
'''