from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.http import HttpResponseRedirect, HttpResponse
from django.core.urlresolvers import reverse
from django.core.exceptions import ObjectDoesNotExist

from aquarilog.models import Aquarium, Equipment, EquipmentLog, WaterLog, WaterLogForm


def index(request):
    aquarium_list = Aquarium.objects.all().order_by('-activeSince')    
    return render(request,
        'aquarilog/index.html',
        {'aquarium_list': aquarium_list}
    )
    
def aquarium_details(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    
    equipment = Equipment.objects.raw(
        "SELECT Equipment.equipmentID, maintenance, action, \
        MAX(logDate) AS lastMaint, \
        DATEDIFF(NOW(), MAX(logDate)) AS daysSinceMaint \
        FROM Equipment \
        LEFT OUTER JOIN EquipmentLog ON EquipmentLog.equipmentID = Equipment.equipmentID \
        WHERE Equipment.aquariumID = %s \
        GROUP BY Equipment.equipmentID",
        [aquarium_id]
    )

    latest_water_logs = \
        WaterLog.objects.filter(aquariumID = aquarium_id) \
            .order_by('testedOn')[:10]
            
    return render(request,
        'aquarilog/aquarium.html',
        {'aquarium': aquarium,
         'equipment' : equipment,
         'latest_water_logs': latest_water_logs,}
    )

def waterlog_entry(request, aquarium_id, waterlog_id):
    try:
        waterlog = WaterLog.objects.get(pk = waterlog_id)
    except ObjectDoesNotExist:
        aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
        waterlog = WaterLog(aquariumID = aquarium, waterLogID = 0)

    # Process Water Log Entries
    if request.method == 'POST':
        waterlog_form = WaterLogForm(request.POST, instance=waterlog)
        if waterlog_form.is_valid():
            waterlog_form.save()
            return HttpResponseRedirect(reverse('aquarilog.views.aquarium_details', args=(aquarium_id,)))
    else:
        waterlog_form = WaterLogForm(instance=waterlog)
#    else:
#        waterlog_form = WaterLogForm()
    return render(request,
        'aquarilog/waterlog.html',
        {'aquarium_id' : aquarium_id,
         'waterlog' : waterlog,
         'waterlog_form': waterlog_form,}
    )   
    
def equipment_details(request, aquarium_id, equipment_id):
    try:
        equipment = Equipment.objects.get(pk = equipment_id)        
        logs = EquipmentLog.objects.filter(equipmentID = equipment_id)        
    except ObjectDoesNotExist:
        aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
        equipment = Equimpent(aquariumID = aquarium, equipmentID = 0)
    return render(request,
        'aquarilog/equipment.html',
        {'equipment' : equipment,
         'logs' : logs,}
    )
    
    
    
