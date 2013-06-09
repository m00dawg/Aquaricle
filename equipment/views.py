from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect, HttpResponse

from aquariums.models import Aquarium
from equipment.models import Equipment, EquipmentLog, EquipmentLogForm

def equipment_list(request, aquarium_id):
    aquarium = get_object_or_404(Aquarium, pk=aquarium_id)
    equipment = Equipment.objects.filter(aquariumID = aquarium_id)
    return render(request,
        'equipment_list.html',
        {'equipment' : equipment,
         'aquarium' : aquarium}
    )

def equipment_details(request, equipment_id):
    equipment = get_object_or_404(Equipment, pk=equipment_id)
    logs = EquipmentLog.objects.filter(equipmentID = equipment_id)
    log_entry = EquipmentLog(equipmentID = equipment)        
    equipment_log_form = EquipmentLogForm(instance=log_entry)

    # Process Equipment Log Entries
    if request.method == 'POST':
        equipment_log_form = EquipmentLogForm(request.POST, instance=log_entry)
        if log_form.is_valid():
            new_log = equipment_log_form.save()
            return HttpResponseRedirect(
                reverse('equipment.views.equipment_details', 
                args=(equipment_id,)))
        
    return render(request,
        'equipment_details.html',
        {'equipment' : equipment,
         'logs' : logs,
         'equipment_log_form' : equipment_log_form}
    )