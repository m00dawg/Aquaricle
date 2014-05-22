from django.db import models
from django.db import connection

class LifeTypeManager(models.Manager):
    def get_kind_summary(self, aquarium_id):
        return self.raw(
            """SELECT Life.lifeTypeID, kind, COUNT(kind) AS kindCount
            FROM Life
            JOIN LifeTypes ON LifeTypes.lifeTypeID = Life.lifeTypeID
            WHERE dateRemoved IS NULL
            AND aquariumID = %s
            GROUP BY kind""",
            [aquarium_id])