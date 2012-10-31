# Create your views here.
"""
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
"""