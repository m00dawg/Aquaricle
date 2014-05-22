from django.db import models
from django.db import connection

class EquipmentManager(models.Manager):
    def get_maintenance(self, aquarium_id):
        return self.raw(
            """SELECT Equipment.equipmentID, maintenance, action, 
            MAX(logDate) AS lastMaint, 
            DATEDIFF(UTC_TIMESTAMP(), MAX(logDate)) AS daysSinceMaint 
            FROM Equipment 
            LEFT OUTER JOIN EquipmentLogs ON EquipmentLogs.equipmentID = Equipment.equipmentID 
            WHERE Equipment.aquariumID = %s
            AND Equipment.active = 'Yes'
            AND EquipmentLog.maintenance = 'Yes'
            GROUP BY Equipment.equipmentID
            ORDER BY daysSinceMaint DESC""",        
            [aquarium_id])