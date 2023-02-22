SELECT agreements.number as num, buf.rent AS rent
FROM agreements 
INNER JOIN (
    SELECT vgroups.agrm_id, SUM(tarifs.rent) AS rent
    FROM vgroups
    INNER JOIN tarifs
    ON vgroups.tar_id = tarifs.tar_id
    WHERE vgroups.archive = 0
    GROUP BY vgroups.agrm_id
    ) AS buf
ON agreements.agrm_id = buf.agrm_id