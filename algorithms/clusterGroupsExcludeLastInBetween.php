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
     * Function would be called as: clusterGroupsExcludeLastInBetween($data, 'A', 'B', 'group', 'id')
     *
     * Function output would be:
        [
            [0, 1],
            [2, 4],
            [5, 6],
            [8, 9]
        ]
     */
    function clusterGroupsExcludeLastInBetween($data, $first, $last, $group, $id)
    {
        $clusters = [];
        $last_group = null;

        foreach ($data as $index => $datum)
        {
            if ($datum[$group] === $first && $last_group !== $first) {
                foreach ($data as $index_2nd_iteration => $datum_2nd_iteration)
                {
                    if ($index < $index_2nd_iteration && $datum_2nd_iteration[$group] === $last) {
                        $clusters[] = [$datum[$id], $datum_2nd_iteration[$id]];
                        break;
                    }
                }
            }
            $last_group = $datum[$group];
        }
        return $clusters;
    }
