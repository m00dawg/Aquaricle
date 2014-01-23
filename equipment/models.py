from django.db import models
from django.forms import ModelForm
from django.forms.widgets import Select
from django.utils import timezone
from aquariums.models import Aquarium
from equipment.managers import EquipmentManager

class Equipment(models.Model):
    equipmentID = models.AutoField(primary_key=True)
    aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')
    installDate = models.DateTimeField(verbose_name='Install Date',editable=True,blank=False)
    active = models.CharField(verbose_name='Active',max_length='3',null=False,blank=False) 
    name = models.CharField(max_length=64,unique=True)
    url = models.URLField(max_length=255,blank=True,null=True)
    maintenanceInterval = models.PositiveSmallIntegerField(verbose_name='Maintenance Interval (Days)',null=True,blank=True)
    comments = models.TextField(blank=True,null=True)
    def __unicode__(self):
        return unicode(self.name)
    class Meta:
        db_table = 'Equipment'
        verbose_name = 'Equipment'
        verbose_name_plural = 'Equipment'
    objects = EquipmentManager();

class EquipmentLog(models.Model):
    equipmentLogID = models.AutoField(primary_key=True)
    equipmentID = models.ForeignKey(Equipment,verbose_name='Equipment',db_column='equipmentID')
    logDate = models.DateTimeField(
        verbose_name='Date',
        editable=True,
        blank=False,
        default=timezone.datetime.now()
    )
    maintenance = models.CharField(verbose_name='Maintenance',max_length='3',null=False,blank=False) 
    action = models.CharField(max_length=64,unique=False)
    class Meta:
        db_table = 'EquipmentLog'
        verbose_name = 'Equipment Log'
        verbose_name_plural = 'Equipment Logs'
        
# Model Forms
class EquipmentForm(ModelForm):
    class Meta:
        ACTIVE_CHOICES = (('Yes', 'Yes'),
                          ('No', 'No'))
        model = Equipment
        exclude = {'equipmentID'}
        widgets = {
            'active' : Select(choices=ACTIVE_CHOICES)
        }

class EquipmentLogForm(ModelForm):
    class Meta:
        MAINTENANCE_CHOICES = (('Yes', 'Yes'),
                               ('No', 'No'))
        model = EquipmentLog
        exclude = {'equipmentID'}
        widgets = {
            'maintenance' : Select(choices=MAINTENANCE_CHOICES)
        }
