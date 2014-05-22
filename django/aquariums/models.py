from django.conf import settings
from django.db import models
from django.forms import ModelForm
#from users.models import AquaricleUser

# Managers
class AquariumManager(models.Manager):
    def get_aquariums(self, user_id):
        return self.raw("""SELECT aquariumID, name, location
            FROM Aquariums
            WHERE userID = %s""",
            [user_id])
    def get_latest_logs(self, aquarium_id, days=30):
        return self.raw("""SELECT aquariumLogID, aquariumID, logDate, summary
            FROM AquariumLogs
            WHERE aquariumID = %s
            AND logDate > DATE_SUB(NOW(), INTERVAL %s DAY)""", [aquarium_id,days])
    
#    def get_log_entries(self, aquarium_id):
#        return self.raw("""SELECT """)

# Objects
class AquariumProfile(models.Model):
    aquariumProfileID = models.AutoField(primary_key=True)
    profileName = models.CharField(verbose_name='Profile Name',max_length=45,editable=True)
    aquariumType = models.CharField(verbose_name='Type',max_length=10)
    temperature = models.DecimalField(verbose_name='Temperature (C)',max_digits=4,decimal_places=2,null=True,blank=True)
    pH = models.DecimalField(max_digits=3,decimal_places=1,null=True,blank=True)
    KH = models.PositiveSmallIntegerField(verbose_name='KH (Degrees)',null=True,blank=True)
    def __unicode__(self):
        return self.profileName
    class Meta:
        db_table = 'AquariumProfiles'    
        verbose_name = 'Aquarium Profile'
        verbose_name_plural = 'Aquarium Profiles'

class Aquarium(models.Model):
    aquariumID = models.AutoField(primary_key=True)
    aquariumProfileID = models.ForeignKey(AquariumProfile,verbose_name='Aquarium Profile',db_column='aquariumProfileID')
    userID = models.ForeignKey(settings.AUTH_USER_MODEL,db_column='userID')
    activeSince = models.DateTimeField(verbose_name='Active Since',editable=True,null=True,blank=True)
    measurementUnits = models.CharField(verbose_name='Measurement Units',
        max_length=8,
        blank=False,
        null=False,
        choices=[('Metric', 'Metric'), ('Imperial', 'Imperial')]
        )    
    capacity = models.DecimalField(verbose_name='Capacity', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    length = models.DecimalField(verbose_name='Length', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    width = models.DecimalField(verbose_name='Width', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    height = models.DecimalField(verbose_name='Height', 
        max_digits=5, decimal_places=2, null=False,blank=False)
    name = models.CharField(max_length=24,unique=True,null=False,blank=False)
    location = models.CharField(max_length=24,blank=True)
    def __unicode__(self):
        return self.name
    class Meta:
        db_table = 'Aquariums'
        verbose_name = 'Aquarium'
        verbose_name_plural = 'Aquariums'
        
    objects = AquariumManager()
    
class AquariumLog(models.Model):
    aquariumLogID = models.AutoField(primary_key=True)
    aquariumID = models.ForeignKey(Aquarium,verbose_name='Aquarium',db_column='aquariumID')
    logDate = models.DateTimeField(verbose_name='Log Date',editable=True,null=False,blank=False)
    summary = models.TextField(blank=True)
    comments = models.TextField(blank=True)
    def __unicode__(self):
        return unicode(self.aquariumLogID)
    class Meta:
        db_table = 'AquariumLogs'
        verbose_name = 'Aquarium Log'
        verbose_name_plural = 'Aquarium Log Entries'
        
# Model Forms
class AquariumForm(ModelForm):
    class Meta:
        model = Aquarium

