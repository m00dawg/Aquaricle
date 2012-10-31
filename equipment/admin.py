from equipment.models import Equipment
from equipment.models import EquipmentLog
from django.contrib import admin

class EquipmentAdmin(admin.ModelAdmin):
    list_display = ('aquariumID', 'name', 'maintenanceInterval')

class EquipmentLogAdmin(admin.ModelAdmin):
    list_display = ('equipmentID', 'logDate', 'maintenance')

admin.site.register(Equipment, EquipmentAdmin)
admin.site.register(EquipmentLog, EquipmentLogAdmin)
