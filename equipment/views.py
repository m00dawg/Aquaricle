from django.shortcuts import render, get_object_or_404
from django.utils import timezone
from django.core.urlresolvers import reverse
from django.http import HttpResponseRedirect, HttpResponse

from aquariums.models import Aquarium
from equipment.models import Equipment, EquipmentLog, EquipmentLogForm

# Create your views here.
def equipment_details(request, equipment_id):
    equipment = get_object_or_404(Equipment, pk=equipment_id)
    logs = EquipmentLog.objects.filter(equipmentID = equipment_id)
    log_entry = EquipmentLog(equipmentID = equipment)        
    log_form = EquipmentLogForm(instance=log_entry)

    # Process Water Log Entries
    if request.method == 'POST':
        log_form = EquipmentLogForm(request.POST, instance=log_entry)
        if log_form.is_valid():
            new_log = log_form.save()
            return HttpResponseRedirect(reverse('equipment.views.equipment_details', args=(equipment_id,)))
        
    return render(request,
        'equipment_details.html',
        {'equipment' : equipment,
         'logs' : logs,
         'log_form' : log_form}
    )