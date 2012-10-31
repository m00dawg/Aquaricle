from waterprofiles.models import WaterProfile
from django.contrib import admin

class WaterProfileAdmin(admin.ModelAdmin):
    list_display = ('name', 'waterType', 'temperature', 'pH', 'KH')

admin.site.register(WaterProfile, WaterProfileAdmin)