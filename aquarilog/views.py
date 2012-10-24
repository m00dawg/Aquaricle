from django.shortcuts import render_to_response
from django.shortcuts import get_object_or_404
from aquarilog.models import Aquarium, WaterLog

def index(request):
    aquarium_list = Aquarium.objects.all().order_by('-activeSince')
    return render_to_response(
        'aquarilog/index.html',
        {'aquarium_list': aquarium_list}
    )

def aquarium(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    latest_water_logs = \
        WaterLog.objects.filter(aquariumID = aquarium_id) \
            .order_by('-testedOn')[:10]
    return render_to_response(
        'aquarilog/aquarium.html',
        {'aquarium': aquarium,
         'latest_water_logs': latest_water_logs}
    )