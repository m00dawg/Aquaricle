from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.http import HttpResponseRedirect, HttpResponse
from django.core.urlresolvers import reverse
from django.db.models import Q, Max
from aquarilog.models import Aquarium, Equipment, EquipmentLog, WaterLog, WaterLogForm


def index(request):
    aquarium_list = Aquarium.objects.all().order_by('-activeSince')
    return render(request,
        'aquarilog/index.html',
        {'aquarium_list': aquarium_list}
    )

def aquarium_details(request, aquarium_id, error_message=None):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    
    equipment = Equipment.objects.raw(
        "SELECT Equipment.equipmentID, maintenance, action, \
        MAX(logDate) AS lastMaint, \
        DATEDIFF(MAX(logDate), NOW()) AS nextMaint \
        FROM Equipment \
        LEFT OUTER JOIN EquipmentLog ON EquipmentLog.equipmentID = Equipment.equipmentID \
        WHERE Equipment.aquariumID = %s \
        GROUP BY Equipment.equipmentID",
        [aquarium_id]
    )

    # Process Water Log Entries
    if request.method == 'POST':
        water_log = WaterLog(aquariumID = aquarium)
        water_log_form = WaterLogForm(request.POST, instance=water_log)
        if water_log_form.is_valid():
            water_log_form.save()
            return HttpResponseRedirect(reverse('aquarilog.views.aquarium_details', args=(aquarium.aquariumID,)))
    else:
        water_log_form = WaterLogForm()
        
    latest_water_logs = \
        WaterLog.objects.filter(aquariumID = aquarium_id) \
            .order_by('testedOn')[:10]
            
    return render(request,
        'aquarilog/aquarium.html',
        {'aquarium': aquarium,
         'equipment' : equipment,
         'equipment_logs' : equipment_logs,
         'latest_water_logs': latest_water_logs,
         'error_message': error_message,
         'water_log_form': water_log_form,}
    )
