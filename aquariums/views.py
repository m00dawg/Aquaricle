from django.shortcuts import render, get_object_or_404
#from django.utils import timezone
#from django.http import HttpResponseRedirect, HttpResponse
#from django.core.urlresolvers import reverse
#from django.core.exceptions import ObjectDoesNotExist

from aquariums.models import Aquarium
from equipment.models import Equipment
from waterlogs.models import WaterLog
from life.models import Life

# Create your views here.

def aquariums(request):
    aquariums = Aquarium.objects.all().order_by('-activeSince')    
    return render(request,
        'aquariums.html',
        {'aquariums': aquariums,}
    )

def aquarium_details(request, aquarium_id):
        aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
        equipment = Equipment.objects.get_maintenance(aquarium_id)
#https://docs.djangoproject.com/en/1.4/topics/db/queries/
#        lifeSummary = AquariumLife.objects.values('kind').annotate(dcount=Count(''))
        life = Life.objects.filter(aquariumID = aquarium_id,dateRemoved=None)
        latest_water_logs = \
            WaterLog.objects.filter(aquariumID = aquarium_id) \
                .order_by('-testedOn')[:15]
        
        return render(request,
            'aquarium_details.html',
            {'aquarium': aquarium,
             'equipment' : equipment,
             'life' : life,
             'latest_water_logs': latest_water_logs,}
        )