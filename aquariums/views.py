from django.shortcuts import render, get_object_or_404
from django.http import HttpResponseRedirect, HttpResponse
from django.core.urlresolvers import reverse
from aquariums.models import Aquarium, AquariumForm
from equipment.models import Equipment
from waterlogs.models import WaterLog
from life.models import Life, LifeTypes

def aquariums(request):
    aquariums = Aquarium.objects.all().order_by('-activeSince')    
    return render(request,
        'aquariums.html',
        {'aquariums': aquariums,}
    )

def add_aquarium(request):
    aquarium = Aquarium()
    aquarium_form = AquariumForm(instance=aquarium)
    # Process New Aquarium
    if request.method == 'POST':
        aquarium_form = AquariumForm(request.POST, instance=aquarium)
        if aquarium_form.is_valid():
            aquarium_form.save()
            return HttpResponseRedirect(reverse('aquariums.views.aquariums'))
    return render(request,
        'add_aquarium.html',
        {'aquarium_form' : aquarium_form}
    )

def aquarium_details(request, aquarium_id):
    if request.user.is_authenticated():
        return True;
        # Do something for authenticated users.
    else:
        return False;
        # Do something for anonymous users.
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    equipment = Equipment.objects.get_maintenance(aquarium_id)
    life = Life.objects.filter(aquariumID = aquarium_id,dateRemoved=None) \
            .order_by('lifeTypeID', 'dateAdded', 'nickname')
    life_kind_summary = LifeTypes.objects.get_kind_summary(aquarium_id)
#    latest_water_logs = \
#        WaterLog.objects.filter(aquariumID = aquarium_id) \
#            .order_by('-testedOn')[:15]  
    latest_water_logs = WaterLog.stats.get_water_logs(aquarium_id, 15)
    last_water_change = WaterLog.stats.get_last_water_change(aquarium_id)
    if aquarium.measurementUnits == 'Metric':
        distanceUnit = 'cm'
        volumeUnit = 'L'
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
         'last_water_change' : last_water_change,
         'distanceUnit' : distanceUnit,
         'volumeUnit' : volumeUnit,}
    )