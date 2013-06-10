from django.db import models
from django.forms import ModelForm
from django.utils import timezone
from aquariums.models import Aquarium
from life.managers import LifeTypeManager

class LifeTypes(models.Model):
    lifeTypeID = models.AutoField(primary_key=True)
    commonName = models.CharField(max_length=64, blank=False, null=False)
    kind = models.CharField(max_length=64,
        blank=False,
        null=False,
        choices=[('Fish', 'Fish'), ('Crustacean', 'Crustacean'),('Plant', 'Plant'), ('Coral', 'Coral'), ('Gastropod', 'Gastropod')],
        )
    kingdom = models.CharField(max_length=64,
        blank=True,
        null=True,
        choices=[('Animalia', 'Animalia'), ('Plantae', 'Plantae'), ('Fungi', 'Fungi')],
        )
    def __unicode__(self):
        return self.commonName
    class Meta:
        db_table = 'LifeTypes'
        verbose_name = 'Life Type'
        verbose_name_plural = 'Life Types'
    objects = LifeTypeManager();

class Life(models.Model):
    lifeID = models.AutoField(primary_key=True)
    lifeTypeID = models.ForeignKey(LifeTypes,verbose_name='Species',db_column='lifeTypeID')
    aquariumID = models.ForeignKey(
        Aquarium,
        verbose_name='Aquarium',
        db_column='aquariumID')
    dateAdded = models.DateTimeField(
        verbose_name='Date Added',
        editable=True,
        blank=False,
        default=timezone.datetime.now())
    dateRemoved = models.DateTimeField(
        verbose_name='Date Removed',
        editable=True,
        blank=True,
        null=True,
        default=None)
    nickname = models.CharField(max_length=64,blank=True,null=True)
    source = models.CharField(max_length=64,blank=True,null=True)
    notes = models.CharField(max_length=256,blank=True,null=True)
    def __unicode__(self):
        return self.nickname
    class Meta:
        db_table = 'Life'
        unique_together = ('aquariumID', 'nickname')
        verbose_name = 'Aquarium Life'
        verbose_name_plural = 'Aquarium Life'

class LifeLog(models.Model):
    lifeLogID = models.AutoField(primary_key=True)
    lifeID = models.ForeignKey(Life,db_column='lifeID')
    logDate = models.DateTimeField(
        verbose_name='Date Added',
        editable=True,
        blank=False,
        default=timezone.datetime.now())
    logEntry = models.CharField(
        verbose_name='Log Entry',
        max_length=128,
        blank=False,
        null=False)
    def __unicode__(self):
        return u'%s - %s' % (self.lifeID, self.logDate)
    class Meta:
        db_table = 'LifeLog'
        verbose_name = 'Life Log'
        verbose_name_plural = 'Life Log'
        
# Model Forms
class LifeForm(ModelForm):
    class Meta:
        model = Life
        fields = ('lifeTypeID', 'dateAdded', 'nickname', 'source')
        exclude = ('aquariumID', 'dateRemoved')

class LifeLogForm(ModelForm):
    class Meta:
        model = LifeLog
        fields = ('logDate', 'logEntry')
        exclude = ('lifeLogID')


