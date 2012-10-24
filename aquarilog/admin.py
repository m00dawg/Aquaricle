from aquarilog.models import WaterProfile
from aquarilog.models import Aquarium
from aquarilog.models import WaterLog
from aquarilog.models import Equipment
from aquarilog.models import EquipmentLog
from django.contrib import admin

class WaterProfileAdmin(admin.ModelAdmin):
    list_display = ('name', 'waterType', 'temperature', 'pH', 'KH')

class WaterLogAdmin(admin.ModelAdmin):
	list_display = ('aquariumID', 'testedOn', 'temperature', 'ammonia', 'nitrites', 
		'nitrates', 'pH', 'KH', 'amountExchanged')

class EquipmentAdmin(admin.ModelAdmin):
    list_display = ('aquariumID', 'name', 'maintenanceInterval')

class EquipmentLogAdmin(admin.ModelAdmin):
    list_display = ('equipmentID', 'logDate', 'maintenance')

admin.site.register(WaterProfile, WaterProfileAdmin)
admin.site.register(Aquarium)
admin.site.register(WaterLog, WaterLogAdmin)
admin.site.register(Equipment, EquipmentAdmin)
admin.site.register(EquipmentLog, EquipmentLogAdmin)
