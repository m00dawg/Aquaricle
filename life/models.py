from django.db import models
from django.utils import timezone
from aquariums.models import Aquarium

class Life(models.Model):
    lifeID = models.AutoField(primary_key=True)
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
        db_table = 'Life'
        verbose_name = 'Life'
        verbose_name_plural = 'Life'

class AquariumLife(models.Model):
    aquariumLifeID = models.AutoField(primary_key=True)
    lifeID = models.ForeignKey(Life,verbose_name='Species',db_column='lifeID')
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
    def __unicode__(self):
        return self.nickname
    class Meta:
        db_table = 'AquariumLife'
        unique_together = ('aquariumID', 'nickname')
        verbose_name = 'Aquarium Life'
        verbose_name_plural = 'Aquarium Life'

class LifeLog(models.Model):
        lifeLogID = models.AutoField(primary_key=True)
        aquariumLifeID = models.ForeignKey(AquariumLife,db_column='aquariumLifeID')
        logDate = models.DateTimeField(
            verbose_name='Date Added',
            editable=True,
            blank=False,
            default=timezone.datetime.now())
        logEntry = models.CharField(max_length=128,blank=False,null=False)
        class Meta:
            db_table = 'LifeLog'
            verbose_name = 'Life Log'
            verbose_name_plural = 'Life Log'