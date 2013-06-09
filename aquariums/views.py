from django.shortcuts import render, get_object_or_404
from aquariums.models import Aquarium
from equipment.models import Equipment
from waterlogs.models import WaterLog
from life.models import Life, LifeTypes

def aquariums(request):
    aquariums = Aquarium.objects.all().order_by('-activeSince')    
    return render(request,
        'aquariums.html',
        {'aquariums': aquariums,}
    )

def aquarium_details(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    equipment = Equipment.objects.get_maintenance(aquarium_id)
    life = Life.objects.filter(aquariumID = aquarium_id,dateRemoved=None) \
            .order_by('dateAdded')
    life_kind_summary = LifeTypes.objects.get_kind_summary(aquarium_id)
    latest_water_logs = \
        WaterLog.objects.filter(aquariumID = aquarium_id) \
            .order_by('-testedOn')[:15]  
    if aquarium.measurementUnits == 'Metric':
        distanceUnit = 'cm'
        volumeUnit = 'liter'
    else:
        distanceUnit = 'in'
        volumeUnit = 'gal'
    return render(request,
        'aquarium_details.html',
        {'aquarium': aquarium,
         'equipment' : equipment,
         'life' : life,
         'life_kind_summary' : life_kind_summary,
         'latest_water_logs': latest_water_logs,
         'distanceUnit' : distanceUnit,
         'volumeUnit' : volumeUnit,}
    )