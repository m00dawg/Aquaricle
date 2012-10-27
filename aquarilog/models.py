from django.db import models
from django.forms import ModelForm
from aquaridawg.fields import EnumField

# Models

class WaterProfile(models.Model):
    waterProfileID = models.AutoField(primary_key=True)
    waterType = EnumField(values=('Freshwater', 'Saltwater', 'Other'),null=True,blank=True)
#    waterType = models.PositiveSmallIntegerField(null=True,blank=True)
    name = models.CharField(max_length=24,unique=True)
    temperature = models.DecimalField(verbose_name='Temperature (C)',max_digits=4,decimal_places=2,null=True,blank=True)
    pH = models.DecimalField(max_digits=3,decimal_places=1,null=True,blank=True)
    KH = models.PositiveSmallIntegerField(null=True,blank=True)
    def __unicode__(self):
        return self.name
    class Meta:
        db_table = 'WaterProfiles'  
        verbose_name = 'Water Profile'
        verbose_name_plural = 'Water Profiles'

class Aquarium(models.Model):
    aquariumID = models.AutoField(primary_key=True)
    waterProfileID = models.ForeignKey(WaterProfile,verbose_name='Water Profile',db_column='waterProfileID')
    activeSince = models.DateTimeField(verbose_name='Active Since',editable=True,null=True,blank=True)
    size = models.PositiveSmallIntegerField(verbose_name='Size (Liter)',null=False,blank=False)
    name = models.CharField(max_length=24,unique=True)
    location = models.CharField(max_length=24,blank=True)
    def __unicode__(self):
        return self.name
    class Meta:
        db_table = 'Aquariums'

class WaterLog(models.Model):
    waterLogID = models.AutoField(primary_key=True)
    aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')
    testedOn = models.DateTimeField(verbose_name='Tested On',editable=True,blank=False)
    temperature = models.DecimalField(verbose_name='Temperature (C)',max_digits=4,decimal_places=2,null=True,blank=True)    
    ammonia = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
    nitrites = models.DecimalField(max_digits=3,decimal_places=2,null=True,blank=True)
    nitrates = models.DecimalField(max_digits=5,decimal_places=2,null=True,blank=True)
    pH = models.DecimalField(verbose_name='pH',max_digits=3,decimal_places=1,null=True,blank=True)
    KH = models.PositiveSmallIntegerField(null=True,blank=True)
    amountExchanged = models.PositiveSmallIntegerField(verbose_name='Water Exchanged',null=True,blank=True)
    comments = models.TextField(blank=True)
    def __unicode__(self):
        return unicode(self.testedOn)
    class Meta:
        db_table = 'WaterLog'
        verbose_name = 'Water Log'
        verbose_name_plural = 'Water Logs'

class Equipment(models.Model):
    equipmentID = models.AutoField(primary_key=True)
    aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')
    installDate = models.DateTimeField(verbose_name='Install Date',editable=True,blank=False)
    name = models.CharField(max_length=64,unique=True)
    url = models.URLField(max_length=255,blank=True)
    maintenanceInterval = models.PositiveSmallIntegerField(verbose_name='Maintenance Interval (Days)',null=True,blank=True)
    comments = models.TextField(blank=True)
    def __unicode__(self):
        return unicode(self.name)
    class Meta:
        db_table = 'Equipment'
        verbose_name = 'Equipment'
        verbose_name_plural = 'Equipment'

class EquipmentLog(models.Model):
    equipmentLogID = models.AutoField(primary_key=True)
    equipmentID = models.ForeignKey(Equipment,verbose_name='Equipment',db_column='equipmentID')
    logDate = models.DateTimeField(verbose_name='Date',editable=True,blank=False)
#    maintenance = EnumField(values=('Yes', 'No'),null=True,blank=False) 
    maintenance = models.CharField(verbose_name='Maintenance',max_length='3',null=False,blank=False) 
#    maintenance = models.PositiveSmallIntegerField(null=True,blank=True)
    action = models.CharField(max_length=24,unique=False)
    class Meta:
        db_table = 'EquipmentLog'
        verbose_name = 'Equipment Log'
        verbose_name_plural = 'Equipment Logs'

# Model Forms
class WaterLogForm(ModelForm):
    class Meta:
        model = WaterLog
        exclude = ('aquariumID')
