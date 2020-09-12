<?php

/**
 * @author Victor Wan
 * @copyright 2020
 */

/*
     * Custom algorithm to cluster groups based on table format.
     * Clustering method:
     *      - A first, B last. 
     *      - no B in between the first A and last B of the cluster.
     *
            +-------+----+
            | group | id |    Cluster = [A.id, B.id]
            +-------+----+
            | A     | 0  |---+
            +-------+----+   |---> C1 = [0, 1]
            | B     | 1  |---+
            +-------+----+
            | A     | 2  |---+
            +-------+----+   |
            | A     | 3  |   |---> C2 = [2, 4]
            +-------+----+   |
            | B     | 4  |---+
            +-------+----+
            | A     | 5  |---+
            +-------+----+   |---> C3 = [5, 6]
            | B     | 6  |---+
            +-------+----+
            | B     | 7  |
            +-------+----+
            | A     | 8  |---+
            +-------+----+   |---> C4 = [8, 9]
            | B     | 9  |---+
            +-------+----+
     *
     * Assume data input is given like so:
     *
        $data = [
            ['group' => 'A', 'id' => 0],
            ['group' => 'B', 'id' => 1],
            ['group' => 'A', 'id' => 2],
            ['group' => 'A', 'id' => 3],
            ['group' => 'B', 'id' => 4],
            ['group' => 'A', 'id' => 5],
            ['group' => 'B', 'id' => 6],
            ['group' => 'B', 'id' => 7],
            ['group' => 'A', 'id' => 8],
            ['group' => 'B', 'id' => 9]
        ];
     *
     * Function would be called as such: clusterGroupsExcludeLastInBetween($data, 'A', 'B', 'group', 'id')
     *
     * Function output would be:
        [
            [0, 1],
            [2, 4],
            [5, 6],
            [8, 9]
        ]
     */
    function clusterHighLow($data, $group, $id, $first, $last=null)
    {
        // Create container to store clusters.
        $clusters = [];
        // Create variable to hold previous iteration group.
        $last_group = null;
        
        // Iterate through dataset, with index.
        foreach ($data as $index => $datum)
        {
            // If current iteration group is $first and the previous iterated group is not $first.
            if ($datum[$group] === $first && $last_group !== $first) {
                // Iterate through dataset again.
                foreach ($data as $index_2nd_iteration => $datum_2nd_iteration)
                {
                    // Search for $last starting from index larger than the group in the outer iteration...
                    if ($index < $index_2nd_iteration &&
                        // ...and if $last is not provided then any group that does not match $first will be counted as the 'last' group.
                        (isset($last) ? $datum_2nd_iteration[$group] === $last : $datum_2nd_iteration[$group] !== $first )) {
                            // If satistified then store IDs in array and push to container.
                            $clusters[] = [$datum[$id], $datum_2nd_iteration[$id]];
                            break;
                    }
                }
            }
            // Store outer iteration group in variable.
            $last_group = $datum[$group];
        }
        return $clusters;
    }
